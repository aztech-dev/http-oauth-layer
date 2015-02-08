<?php

namespace Aztech\Layers\Oauth\Phinject;

use Aztech\Phinject\Activator;
use Aztech\Phinject\Container;
use Aztech\Phinject\Util\ArrayResolver;
use Aztech\Layers\Oauth\ClientAdapterCollection;
use Aztech\Layers\Oauth\Elements\SocialLogin;
use Aztech\Layers\Oauth\Elements\SocialLoginCallback;

class OauthActivator implements Activator
{

    private static $oauthMap = [
        'facebook' => 'League\OAuth2\Client\Provider\Facebook',
        'github' => 'League\OAuth2\Client\Provider\Github',
        'bitbucket' => 'League\OAuth1\Client\Server\Bitbucket'
    ];

    public function createInstance(Container $container, ArrayResolver $serviceConfig, $serviceName)
    {
        $oauthProviders = new ClientAdapterCollection();
        $session = $container->resolve($serviceConfig->resolve('session'));

        $oauthProviderConfig = $container->resolve($serviceConfig->resolve('oauth', [], false));

        foreach ($oauthProviderConfig as $key => $params) {
            $oauthProviders->add($key, new self::$oauthMap[$key]($params));
        }

        if ($serviceConfig->resolve('callback', false) === false) {
            return new SocialLogin($session, $oauthProviders);
        }

        $callback = new SocialLoginCallback($session, $oauthProviders);
        $callback->setNextController($container->resolve($serviceConfig->resolve('next')));

        return $callback;
    }
}