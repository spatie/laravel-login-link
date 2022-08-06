<?php

namespace Spatie\LoginLink\Exceptions;

use Exception;

class NotAllowedInCurrentEnvironment extends Exception
{
    public static function make(array $allowedEnvironments): self
    {
        $currentEnvironment = app()->environment();

        $allowedEnvironments = collect($allowedEnvironments)
            ->map(fn (string $environment) => "`{$environment}`")
            ->join(', ', ' and');

        return new self("You can not use a login link when environment is set to `{$currentEnvironment}`. The environment should be one of: {$allowedEnvironments}.");
    }
}
