<?php

namespace Spatie\LocalLogin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\LocalLogin\LocalLogin
 */
class LocalLogin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-local-login';
    }
}
