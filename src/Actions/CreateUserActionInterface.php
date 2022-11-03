<?php

namespace Spatie\LoginLink\Actions;

use Illuminate\Contracts\Auth\Authenticatable;

interface CreateUserActionInterface
{
    public function execute(string $authenticatableClass, array $attributes): Authenticatable;
}
