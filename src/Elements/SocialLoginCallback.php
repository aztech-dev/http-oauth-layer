<?php

namespace Aztech\Layers\Oauth\Elements;

use Aztech\Layers\Oauth\InvalidAuthorizationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Aztech\Layers\Oauth\ClientAdapterCollection;
use Aztech\Layers\Layer;
use Aztech\Layers\Oauth\LoginManager;

class SocialLoginCallback
{

    private $session;

    private $providers;

    private $prefix;

    private $loginHandler;

    private $loginManager;

    private $nextController;

    public function __construct(SessionInterface $session, LoginManager $manager, ClientAdapterCollection $providers, $prefix = '')
    {
        $this->session = $session;
        $this->providers = $providers;
        $this->prefix = $prefix;
        $this->loginManager = $manager;
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

        try {
            $credentials = $provider->getCredentials($request, $this->session);
            $this->loginManager->setCredentials($credentials);
        }
        catch (InvalidAuthorizationException $exception) {
            $this->loginManager->setLastError($exception);
        }

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