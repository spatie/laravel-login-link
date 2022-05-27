<?php

namespace Spatie\LoginLink\Exceptions;

use Exception;

class InvalidUserClass extends Exception
{
    public static function notFound(): self
    {
        // to implement
    }
}
