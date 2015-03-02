<?php

namespace Aztech\Layers\Oauth;

class LoginManager
{

    /**
     * @var Credentials|null
     */
    private $credentials = null;

    /**
     * @var \Exception|null
     */
    private $lastError = null;

    /**
     * @param \Exception $exception
     */
    public function setLastError(\Exception $exception)
    {
        $this->lastError = $exception;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return $this->lastError != null;
    }

    /**
     * @return \Exception|null
     */
    public function getError()
    {
        return $this->lastError;
    }

    /**
     * @param Credentials $credentials
     */
    public function setCredentials(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @return bool
     */
    public function hasCredentials()
    {
        return $this->credentials != null;
    }

    /**
     * @return Credentials|null
     */
    public function getCredentials()
    {
        if (! $this->hasCredentials()) {
            throw new UnauthenticatedUserException();
        }

        return $this->credentials;
    }
}