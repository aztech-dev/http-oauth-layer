<?php

namespace Aztech\Layers\Oauth;

interface Credentials
{

    public function getUid();

    public function getLastName();

    public function getFirstName();

    public function getName();

    public function getEmail();

    public function getAvatar();
}