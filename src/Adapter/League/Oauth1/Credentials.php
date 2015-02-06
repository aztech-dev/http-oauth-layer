<?php

namespace Aztech\Layers\Oauth\Adapter\League\Oauth1;

use Aztech\Layers\Oauth\Credentials as OauthCredentials;
use League\OAuth1\Client\Credentials\TokenCredentials;
use League\OAuth1\Client\Server\User;

class Credentials implements OauthCredentials
{

    /**
    *
    * @var TokenCredentials
    */
    private $credentials;

    /**
     *
     * @var User
     */
    private $user;

    /**
     *
     * @param TokenCredentials $credentials
     * @param User $user
     */
    public function __construct(TokenCredentials $credentials, User $user)
    {
        $this->credentials = $credentials;
        $this->user = $user;
    }

    /**
     * (non-PHPdoc)
     * @see \Aztech\Layers\Oauth\Credentials::getUid()
     */
    public function getUid()
    {
        return $this->user->uid;
    }

    /**
     * (non-PHPdoc)
     * @see \Aztech\Layers\Oauth\Credentials::getName()
     */
    public function getName()
    {
        return $this->user->name;
    }
}