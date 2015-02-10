<?php

namespace Aztech\Layers\Oauth\Elements;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Aztech\Layers\Oauth\ClientAdapterCollection;
use Guzzle\Http\Message\Response;
use Aztech\Layers\Oauth\LoginManager;

class SocialLogin
{

    private $session;

    private $providers;

    private $prefix;

    private $loginManager;

    public function __construct(SessionInterface $session, ClientAdapterCollection $providers, $prefix = '')
    {
        $this->session = $session;
        $this->providers = $providers;
        $this->prefix = $prefix;
    }

    public function __invoke(Request $request)
    {
        $providerName = $this->prefix . $request->get('provider');
        $provider = $this->providers->get($providerName);

        $provider->storeAuthorizationData($request, $this->session);
        $authorizationUrl = $provider->getAuthorizationUrl();

        header('Location:' . $authorizationUrl);
        exit();
    }
}