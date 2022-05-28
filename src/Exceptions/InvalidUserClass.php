<?php

namespace Spatie\LoginLink\Exceptions;

use Exception;

class InvalidUserClass extends Exception
{
    public static function notFound(): self
    {
        return new self('Could not determine which user class should be logged in. Make sure to specify a user class in the `user_model` key of the `login-link` config file.');
    }
}
