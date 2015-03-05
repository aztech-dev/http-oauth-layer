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
     *
     * @see \Aztech\Layers\Oauth\Credentials::getUid()
     */
    public function getUid()
    {
        return $this->user->uid;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aztech\Layers\Oauth\Credentials::getName()
     */
    public function getName()
    {
        return $this->user->name;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aztech\Layers\Oauth\Credentials::getLastName()
     */
    public function getLastName()
    {
        return $this->user->lastName;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aztech\Layers\Oauth\Credentials::getFirstName()
     */
    public function getFirstName()
    {
        return $this->user->firstName;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aztech\Layers\Oauth\Credentials::getEmail()
     */
    public function getEmail()
    {
        return $this->user->email;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Aztech\Layers\Oauth\Credentials::getAvatar()
     */
    public function getAvatar()
    {
        return $this->user->imageUrl;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return '';
    }
}