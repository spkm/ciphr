<?php

namespace spkm\Ciphr;

use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;
use spkm\Ciphr\Exceptions\GoneException;
use spkm\Ciphr\Exceptions\InvalidRequestException;
use spkm\Ciphr\Exceptions\NotFoundException;
use spkm\Ciphr\Exceptions\ServerErrorException;
use spkm\Ciphr\Exceptions\UnauthorizedException;
use spkm\Ciphr\Requests\ItemRequest;

class CiphrApiException extends RequestException
{
    public function __construct(Response $response)
    {
        parent::__construct($response, $this->describe($response));
    }

    /**
     * The exception for Ciphr's error status: a subclass for the errors callers handle differently.
     *
     * Two of Ciphr's answers don't match their status. A GET for an id Ciphr doesn't have is a 500 with no error
     * list rather than a 404, and a request the key isn't allowed is a 400 saying "Unauthorized access".
     */
    public static function fromResponse(Response $response): self
    {
        $status = $response->status();

        return match (true) {
            $status === 404 => new NotFoundException($response),
            $status === 410 => new GoneException($response),
            self::refusesAccess($response) => new UnauthorizedException($response),
            $status === 400 => new InvalidRequestException($response),
            in_array($status, [401, 403], true) => new UnauthorizedException($response),
            self::isMissingRecord($response) => new NotFoundException($response),
            $status === 408 || $response->serverError() => new ServerErrorException($response),
            default => new self($response),
        };
    }

    /**
     * Whether Ciphr refused the key access to the method or entity, which it reports as a 400.
     */
    public static function refusesAccess(Response $response): bool
    {
        return $response->status() === 400 && preg_match('/unauthori[sz]ed/i', (string) self::messageIn($response)) === 1;
    }

    /**
     * Whether a GET by id found nothing: Ciphr answers with a 500 and no errors rather than a 404.
     */
    public static function isMissingRecord(Response $response): bool
    {
        return $response->status() === 500
            && $response->getRequest() instanceof ItemRequest
            && self::errorsIn($response) === [];
    }

    /**
     * The errors Ciphr listed, each with a Name and a Description; empty when the body isn't Ciphr's error JSON.
     *
     * @return list<array{Name: string, Description: string}>
     */
    public function errors(): array
    {
        return self::errorsIn($this->getResponse());
    }

    /**
     * What Ciphr said: its Message, then each error as "Name: Description", joined with "; ". Null when it said
     * nothing the package can read.
     */
    public function ciphrMessage(): ?string
    {
        return self::messageIn($this->getResponse());
    }

    protected function describe(Response $response): string
    {
        $text = 'Ciphr responded '.$response->status().' to '.self::requestLine($response);
        $page = self::htmlTitle($response);

        if ($page !== null) {
            return $text.' with an HTML '.($response->successful() ? 'page instead of JSON' : 'error page').($page !== '' ? " ('$page')" : '');
        }

        if ($response->successful() && $response->body() !== '' && ! json_validate($response->body())) {
            return $text.' with a body that is not JSON';
        }

        $message = self::messageIn($response);

        return $text.($message !== null ? ": $message" : '');
    }

    /**
     * The failed request's method and path, without its query string.
     */
    protected static function requestLine(Response $response): string
    {
        $pendingRequest = $response->getPendingRequest();

        return $pendingRequest->getMethod()->value.' '.(parse_url($pendingRequest->getUrl(), PHP_URL_PATH) ?: '/');
    }

    /**
     * The title of an HTML error page ('' when it has none), or null when Ciphr didn't send HTML.
     */
    private static function htmlTitle(Response $response): ?string
    {
        if (! str_contains(strtolower((string) $response->header('Content-Type')), 'html') && ! str_starts_with(ltrim($response->body()), '<')) {
            return null;
        }

        return preg_match('#<title>(.*?)</title>#is', $response->body(), $matches) ? trim(html_entity_decode($matches[1])) : '';
    }

    /**
     * @return list<array{Name: string, Description: string}>
     */
    private static function errorsIn(Response $response): array
    {
        $body = json_decode($response->body(), true);
        $errors = is_array($body) ? $body['Error'] ?? null : null;

        if (! is_array($errors)) {
            return [];
        }

        return collect($errors)
            ->filter(fn (mixed $error) => is_array($error))
            ->map(fn (array $error) => [
                'Name' => is_scalar($error['Name'] ?? null) ? (string) $error['Name'] : '',
                'Description' => is_scalar($error['Description'] ?? null) ? (string) $error['Description'] : '',
            ])
            ->values()
            ->all();
    }

    private static function messageIn(Response $response): ?string
    {
        $body = json_decode($response->body(), true);
        $parts = [];

        if (is_array($body) && is_scalar($body['Message'] ?? null) && trim((string) $body['Message']) !== '') {
            $parts[] = trim((string) $body['Message']);
        } elseif (! is_array($body) && ! json_validate($response->body()) && self::htmlTitle($response) === null && trim($response->body()) !== '') {
            // A plain-text answer, such as "Message: An unauthorized request has been made".
            $parts[] = trim((string) preg_replace('/^Message:\s*/i', '', trim(substr($response->body(), 0, 200))));
        }

        foreach (self::errorsIn($response) as $error) {
            $part = trim(implode(': ', array_filter([$error['Name'], $error['Description']], fn (string $part) => $part !== '')));

            if ($part !== '') {
                $parts[] = $part;
            }
        }

        return $parts === [] ? null : implode('; ', array_map(fn (string $part) => rtrim($part, '.'), $parts));
    }
}
