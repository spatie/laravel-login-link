<?php

namespace Spatie\LoginLink\Exceptions;

use Exception;

class NotAllowedInCurrentHost extends Exception
{
    public static function make(string $currentHost, array $allowedHosts): self
    {
        $allowedHosts = collect($allowedHosts)
            ->map(fn (string $host) => "`{$host}`")
            ->join(', ', ' and');

        return new self("You can not use a login link when host is `{$currentHost}`. The host should be one of: {$allowedHosts}.");
    }
}
