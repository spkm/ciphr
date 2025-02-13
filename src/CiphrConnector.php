<?php

namespace spkm\ciphr;

use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\PagedPaginator;

class CiphrConnector extends Connector
{
    public function __construct(protected readonly string $customerPortal, public readonly string $token){}

    public function resolveBaseUrl(): string
	{
		return "https://{$this->customerPortal}.myciphr247.com/api";
	}

    protected function defaultHeaders(): array
    {
        return [
            'Content-type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->token,'apikey');
    }

    public function paginate(Request $request): PagedPaginator
    {
        return new class(connector: $this, request: $request) extends PagedPaginator
        {
            protected ?int $perPageLimit = 100;
            //Total number of records can be fetched using the count query parameter as a separate request
            // p (page) and l (limit) query parameters are used to paginate the results
            // s (sort) must be used with p & l to sort the results  i.e. ?s=+ID&p=1&l=100

            protected function isLastPage(Response $response): bool
            {
                return count($response->collect()->flatten()) === 0;
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $response->json();
            }

            protected function applyPagination(Request $request): Request
            {
                if($this->currentPage === 0){
                    $this->currentPage = 1; //If 0 the API ignores pagination & returns all results.
                }
                $request->query()->add('p', $this->currentPage);

                $sortKey = $request->config()->get('paginationSortKey');
                $request->query()->add('s', "+{$sortKey}");

                if(isset($this->perPageLimit)) {
                    $request->query()->add('l', $this->perPageLimit);
                }

                return $request;
            }
        };
    }
}
