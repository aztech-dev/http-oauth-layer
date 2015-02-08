<?php

namespace Aztech\Layers\Oauth\Elements;

use Aztech\Layers\LayerBuilder;
use Aztech\Layers\Oauth\ClientAdapterCollection;
use Aztech\Phinject\Container;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SocialLoginLayerBuilder implements LayerBuilder
{

    private $container;

    private $session;

    private $providers;

    private $prefix;

    public function __construct(Container $container, SessionInterface $session, array $providers, $prefix = '')
    {
        $this->container = $container;
        $this->session = $session;
        $this->providers = new ClientAdapterCollection($providers);
        $this->prefix = $prefix;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aztech\Layers\LayerBuilder::buildLayer()
     */
    public function buildLayer(Layer $nextLayer, array $arguments)
    {
        if ($arguments[0]) {
            $layer = new SocialLoginCallback($this->session, $this->providers, $this->prefix);

            $layer->setNextController($nextLayer);

            if (is_callable($arguments[0])) {
                $layer->onUserLoggedIn($this->container->resolve($arguments[0]));
            }
        }

        return new SocialLogin($this->session, $this->providers, $this->prefix);
    }
}