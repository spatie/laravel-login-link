<?php

use Spatie\LoginLink\Tests\TestSupport\TestCase;

uses(TestCase::class)->in(__DIR__);

function expectUserToBeLoggedIn(array $attributes = [], ?string $guard = null)
{
    expect(auth($guard)->check())->toBeTrue();

    foreach ($attributes as $name => $value) {
        expect(auth($guard)->user()->{$name})->toBe($value);
    }
}

function expectNotLoggedIn(?string $guard = null)
{
    expect(auth($guard)->check())->toBeFalse();
}
