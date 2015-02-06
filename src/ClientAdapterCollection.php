<?php

namespace Aztech\Layers\Oauth;

use League\OAuth1\Client\Server\Server;
use League\OAuth2\Client\Provider\ProviderInterface;

class ClientAdapterCollection
{

    /**
     *
     * @var ClientAdapter[]
     */
    private $adapters;

    /**
     *
     * @var ClientAdapterFactory
     */
    private $factory;

    /**
     *
     * @param ClientAdapter[] $adapters Array of adapters indexed by names
     */
    public function __construct(array $adapters = [])
    {
        $this->factory = new ClientAdapterFactory();

        foreach ($adapters as $name => $adapter) {
            $this->add($name, $adapter);
        }
    }

    /**
     *
     * @param string $name
     * @param ClientAdapter|Server|ProviderInterface $adapter
     */
    public function add($name, $adapter)
    {
        $this->adapters[$name] = $this->factory->getAdapter($adapter, $name);
    }

    /**
     *
     * @param string $name
     * @return boolean
     */
    public function has($name)
    {
        return isset($this->adapters[$name]);
    }

    /**
     *
     * @param string $name
     * @return ClientAdapter
     *
     * @throws \InvalidArgumentException
     */
    public function get($name)
    {
        if (! isset($name)) {
            throw new \InvalidArgumentException();
        }

        return $this->adapters[$name];
    }
}