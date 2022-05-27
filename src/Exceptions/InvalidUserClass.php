<?php

namespace Spatie\LoginLink\Exceptions;

use Exception;

class InvalidUserClass extends Exception
{
    public static function notFound(): self
    {
        return new self('Could not determine which User class should be logged in.');
    }
}
