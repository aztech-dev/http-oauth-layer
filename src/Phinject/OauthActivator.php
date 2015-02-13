<?php

namespace Aztech\Layers\Oauth\Phinject;

use Aztech\Phinject\Activator;
use Aztech\Phinject\ConfigurationAware;
use Aztech\Phinject\Container;
use Aztech\Phinject\Util\ArrayResolver;
use Aztech\Layers\Oauth\ClientAdapterCollection;
use Aztech\Layers\Oauth\Elements\SocialLogin;
use Aztech\Layers\Oauth\Elements\SocialLoginCallback;
use Aztech\Layers\Oauth\LoginManager;

class OauthActivator implements Activator, ConfigurationAware
{

    private static $oauthMap = [
        'bitbucket' => 'League\OAuth1\Client\Server\Bitbucket',
        'twitter'   => 'League\OAuth1\Client\Server\Twitter',
        'facebook'  => 'League\OAuth2\Client\Provider\Facebook',
        'github'    => 'League\OAuth2\Client\Provider\Github',
        'google'    => 'League\OAuth2\Client\Provider\Google'
    ];

    private $configuration;

    private $managerKey;

    private $manager;

    private $map;

    public function __construct()
    {
        $this->map = self::$oauthMap;
        $this->manager = new LoginManager();
    }

    public function setConfiguration(ArrayResolver $configurationNode)
    {
        $this->configuration = $configurationNode;

        if ($providers = $this->configuration->resolve('providers', false)) {
            $this->map = array_merge($this->map, $providers->extract());
        }

        $this->managerKey = $configurationNode->resolveStrict('manager');
    }

    public function createInstance(Container $container, ArrayResolver $serviceConfig, $serviceName)
    {
        if (! $container->has($this->managerKey)) {
            $container->bind($this->managerKey, $this->manager);
        }

        $oauthProviders = new ClientAdapterCollection();
        $session = $container->resolve($serviceConfig->resolveStrict('session'));

        $oauthProviderConfig = $container->resolve($serviceConfig->resolve('oauth', [], false));

        foreach ($oauthProviderConfig as $key => $params) {
            $oauthProviders->add($key, new $this->map[$key]($params));
        }

        if ($serviceConfig->resolve('callback', false) === false) {
            return new SocialLogin($session, $oauthProviders);
        }

        $callback = new SocialLoginCallback($session, $this->manager, $oauthProviders);
        $callback->setNextController($container->resolve($serviceConfig->resolve('next')));

        return $callback;
    }
}
