<?php

namespace Aztech\Layers\Oauth;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

interface ClientAdapter
{

    /**
     *
     * @param Request $request
     * @param SessionInterface $session
     */
    public function storeAuthorizationData(Request $request, SessionInterface $session);

    /**
     *
     * @return string
     */
    public function getAuthorizationUrl();

    /**
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return boolean
     */
    public function canGetCredentials(Request $request, SessionInterface $session);

    /**
     *
     * @param Request $request
     * @param SessionInterface $session
     * @return Credentials
     *
     * @throws InvalidAuthorizationException
     */
    public function getCredentials(Request $request, SessionInterface $session);
}