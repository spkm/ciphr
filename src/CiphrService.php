<?php

namespace spkm\Ciphr;

use BadMethodCallException;
use InvalidArgumentException;

/**
 * What the Ciphr facade resolves: the default connector, built from config('ciphr') the first time it is needed, and
 * any named connections.
 *
 * @mixin CiphrConnector
 */
class CiphrService
{
    protected CiphrConnector $connector;

    /** @var array<string, CiphrConnector> */
    protected array $connections = [];

    /**
     * The connector for another portal, configured under ciphr.connections.{name}.
     */
    public function connection(string $name): CiphrConnector
    {
        $config = config("ciphr.connections.$name");

        if (! is_array($config)) {
            throw new InvalidArgumentException('No Ciphr connection named "'.$name.'" is configured in ciphr.connections.');
        }

        return $this->connections[$name] ??= $this->connect($config, 'The Ciphr connection "'.$name.'"');
    }

    /**
     * @param  array<mixed>  $parameters
     */
    public function __call(string $method, array $parameters): mixed
    {
        $this->connector ??= $this->connect((array) config('ciphr'), 'The default Ciphr connection');

        if (method_exists($this->connector, $method)) {
            return $this->connector->{$method}(...$parameters);
        }

        throw new BadMethodCallException("Method {$method} does not exist.");
    }

    /**
     * @param  array<string, mixed>  $config
     */
    private function connect(array $config, string $connection): CiphrConnector
    {
        foreach (['portal', 'api_key'] as $setting) {
            if (! is_string($config[$setting] ?? null) || $config[$setting] === '') {
                throw new InvalidArgumentException("$connection needs a $setting.");
            }
        }

        $baseUrl = $config['base_url'] ?? null;

        return new CiphrConnector($config['portal'], $config['api_key'], is_string($baseUrl) && $baseUrl !== '' ? $baseUrl : null);
    }
}
