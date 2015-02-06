<?php

namespace Aztech\Layers\Oauth\Adapter\League\Oauth2;

use Aztech\Layers\Oauth\Credentials as OauthCredentials;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Entity\User;

class Credentials implements OauthCredentials
{

    /**
    *
    * @var AuthorizationCode
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
    public function __construct(AuthorizationCode $credentials, User $user)
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