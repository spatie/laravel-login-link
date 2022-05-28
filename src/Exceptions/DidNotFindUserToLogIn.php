<?php

namespace Spatie\LoginLink\Exceptions;

use Exception;

class DidNotFindUserToLogIn extends Exception
{
    public static function make(): self
    {
        return new self("Did not find the user that needs to be logged in. You could set the `automatically_create_missing_users` option in the `login-link` config file to `true` to automatically create it. ");
    }
}
