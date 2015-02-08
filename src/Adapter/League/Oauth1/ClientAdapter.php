<?php

namespace Aztech\Layers\Oauth\Adapter\League\Oauth1;

use Aztech\Layers\Oauth\ClientAdapter as OauthClientAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use League\OAuth1\Client\Server\Server;
use League\OAuth1\Client\Credentials\CredentialsException;
use Aztech\Layers\Oauth\InvalidAuthorizationException;

class ClientAdapter implements OauthClientAdapter
{

    private $provider;

    private $providerName;

    private $temporaryCredentials;

    public function __construct(Server $provider, $providerName = '')
    {
        if (trim($providerName) == '') {
            $providerName = get_class($provider);
        }

        $this->provider = $provider;
        $this->providerName = (string) $providerName;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aztech\Layers\Oauth\ClientAdapter::isPreAuthorizationRequired()
     */
    public function isAuthorizationRequired(Request $request)
    {
        return ! $this->hasAuthorizationData($request);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aztech\Layers\Oauth\ClientAdapter::storePreAuthorizationData()
     */
    public function storeAuthorizationData(Request $request, SessionInterface $session)
    {
        $this->temporaryCredentials = $this->provider->getTemporaryCredentials();
        $session->set($this->providerName, $this->temporaryCredentials);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aztech\Layers\Oauth\ClientAdapter::getAuthorizationUrl()
     */
    public function getAuthorizationUrl()
    {
        return $this->provider->getAuthorizationUrl($this->temporaryCredentials);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aztech\Layers\Oauth\ClientAdapter::hasAuthorizationData()
     */
    public function canGetCredentials(Request $request, SessionInterface $session)
    {
        return $request->get('oauth_token', false) !== false && $request->get('oauth_verifier', false) !== false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aztech\Layers\Oauth\ClientAdapter::getCredentials()
     */
    public function getCredentials(Request $request, SessionInterface $session)
    {
        $temporaryIdentifier = $request->get('oauth_token');
        $verifier = $request->get('oauth_verifier');
        $temporaryCredentials = $session->get($this->providerName);

        $session->remove($this->providerName);

        if (! $temporaryCredentials) {
            throw new InvalidAuthorizationException();
        }

        try {
            $credentials = $this->provider->getTokenCredentials($temporaryCredentials, $temporaryIdentifier, $verifier);
            $user = $this->provider->getUserDetails($credentials);

            return new Credentials($credentials, $user);
        }
        catch (CredentialsException $ex) {
            throw new InvalidAuthorizationException('', 0, $ex);
        }
    }
}