<?php

namespace Spatie\LoginLink\Tests\TestSupport\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\LoginLink\Actions\CreateUserActionInterface;

class TestCreateUserAction implements CreateUserActionInterface
{
    public const EMAIL = 'custom-action@spatie.be';

    public function execute(string $authenticatableClass, array $attributes): Authenticatable
    {
        return (new $authenticatableClass())->forceFill([...$attributes, 'email' => self::EMAIL]);
    }
}
