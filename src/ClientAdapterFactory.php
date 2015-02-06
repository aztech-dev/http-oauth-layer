<?php

namespace Aztech\Layers\Oauth;

use League\OAuth1\Client\Server\Server;
use League\OAuth2\Client\Provider\ProviderInterface;
use Aztech\Layers\Oauth\Adapter\League\Oauth1\ClientAdapter as LeagueOauth1ClientAdapter;
use Aztech\Layers\Oauth\Adapter\League\Oauth2\ClientAdapter as LeagueOauth2ClientAdapter;

class ClientAdapterFactory
{
    /**
    *
    * @param ClientAdapter|Server|ProviderInterface $provider
    * @return ClientAdapter
    */
    public function getAdapter($provider, $name = '')
    {
        if ($provider instanceof ClientAdapter) {
            return $provider;
        }

        if ($provider instanceof Server) {
            return new LeagueOauth1ClientAdapter($provider, $name);
        }

        if ($provider instanceof ProviderInterface) {
            return new LeagueOauth2ClientAdapter($provider, $name);
        }
    }
}