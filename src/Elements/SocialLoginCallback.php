<?php

namespace Aztech\Layers\Oauth\Elements;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Aztech\Layers\Oauth\ClientAdapterCollection;
use Aztech\Layers\Layer;

class SocialLoginCallback
{

    private $session;

    private $providers;

    private $prefix;

    private $loginHandler;

    private $nextController;

    public function __construct(SessionInterface $session, ClientAdapterCollection $providers, $prefix = '')
    {
        $this->session = $session;
        $this->providers = $providers;
        $this->prefix = $prefix;
    }

    public function setNextController(Layer $controller)
    {
        $this->nextController = $controller;
    }

    public function onUserLoggedIn(callable $handler)
    {
        $this->loginHandler = $handler;
    }

    public function __invoke(Request $request)
    {
        $providerName = $this->prefix . $request->get('provider');
        $provider = $this->providers->get($providerName);

        $credentials = $provider->getCredentials($request, $this->session);

        if ($this->loginHandler) {
            $handler = $this->loginHandler;
            $handler($request->get('provider'), $credentials);
        }

        if ($this->nextController) {
            $controller = $this->nextController;

            return $controller($request);
        }

        return [];
    }
}