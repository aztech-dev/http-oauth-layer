<?php

namespace Aztech\Layers\Oauth;

class LoginManager
{

    private $credentials = null;

    public function setCredentials(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    public function hasCredentials()
    {
        return $this->credentials != null;
    }

    public function getCredentials()
    {
        if (! $this->hasCredentials()) {
            throw new UnauthenticatedUserException();
        }

        return $this->credentials;
    }
}