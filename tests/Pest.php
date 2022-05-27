<?php

use Spatie\LoginLink\Tests\TestSupport\TestCase;

uses(TestCase::class)->in(__DIR__);

function expectUserToBeLoggedIn(array $attributes = [])
{
    expect(auth()->check())->toBeTrue();

    foreach ($attributes as $name => $value) {
        expect(auth()->user()->$name)->toBe($value);
    }
}
