<?php

namespace Subjig\Report\Model;

class UserLoginRequest extends UserFactory
{
    public string $username;
    public string $password;
}