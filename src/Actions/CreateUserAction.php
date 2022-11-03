<?php

namespace Spatie\LoginLink\Actions;

use Illuminate\Contracts\Auth\Authenticatable;

class CreateUserAction implements CreateUserActionInterface
{
    public function execute(string $authenticatableClass, array $attributes): Authenticatable
    {
        return $authenticatableClass::factory()->create($attributes);
    }
}
