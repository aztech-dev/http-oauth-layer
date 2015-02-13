<?php

namespace Aztech\Layers\Oauth\Adapter\League\Oauth2;

use Aztech\Layers\Oauth\ClientAdapter as OauthClientAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use League\OAuth2\Client\Provider\ProviderInterface;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Exception\IDPException;
use Aztech\Layers\Oauth\InvalidAuthorizationException;

class ClientAdapter implements OauthClientAdapter
{

    private $authorizationUrl;

    private $provider;

    private $providerName;

    public function __construct(ProviderInterface $provider, $providerName = '')
    {
        if (trim($providerName) == '') {
            $providerName = get_class($provider);
        }

        $this->provider = $provider;
        $this->providerName = (string) $providerName;
    }

    /**
     * (non-PHPdoc)
     * @see \Aztech\Layers\Oauth\ClientAdapter::isAuthorizationRequired()
     */
    public function isAuthorizationRequired(Request $request, SessionInterface $session)
    {
        return $request->get('code', false) == false;
    }

    /**
     * (non-PHPdoc)
     * @see \Aztech\Layers\Oauth\ClientAdapter::storeAuthorizationData()
     */
    public function storeAuthorizationData(Request $request, SessionInterface $session)
    {
        $this->getAuthorizationUrl();

        if ($this->provider instanceof AbstractProvider) {
            $session->set($this->providerName, $this->provider->state);
        }
    }

    /**
     * (non-PHPdoc)
     * @see \Aztech\Layers\Oauth\ClientAdapter::getAuthorizationUrl()
     */
    public function getAuthorizationUrl()
    {
        if (! $this->authorizationUrl) {
            $this->authorizationUrl = $this->provider->getAuthorizationUrl();
        }

        return $this->authorizationUrl;
    }

    /**
     * (non-PHPdoc)
     * @see \Aztech\Layers\Oauth\ClientAdapter::canGetCredentials()
     */
    public function canGetCredentials(Request $request, SessionInterface $session)
    {
        return
            $request->get('code', false) !== false &&
            $request->get('state', false) !== false &&
            $request->get('state') === $session->get($this->providerName, false);
    }

    /**
     * (non-PHPdoc)
     * @see \Aztech\Layers\Oauth\ClientAdapter::getCredentials()
     */
    public function getCredentials(Request $request, SessionInterface $session)
    {
        try {
            $token = $this->provider->getAccessToken('authorization_code', [ 'code' => $request->get('code') ]);
            $user = $this->provider->getUserDetails($token);
        }
        catch (IDPException $exception) {
            throw new InvalidAuthorizationException('Authorization failed', 0, $exception);
        }

        return new Credentials($token, $user);
    }
}