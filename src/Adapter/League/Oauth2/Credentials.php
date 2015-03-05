<?php

namespace Aztech\Layers\Oauth\Adapter\League\Oauth2;

use Aztech\Layers\Oauth\Credentials as OauthCredentials;
use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Token\AccessToken;

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
    public function __construct(AccessToken $credentials, User $user)
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

    public function getLastName()
    {
        return $this->user->lastName;
    }

    public function getFirstName()
    {
        return $this->user->firstName;
    }

    public function getEmail()
    {
        return $this->user->email;
    }

    public function getAvatar()
    {
        return $this->user->imageUrl;
    }

    public function getToken()
    {
        return $this->credentials->accessToken;
    }
}