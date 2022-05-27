<?php

use function Pest\Laravel\post;

it('can test', function () {
    post(route('loginLinkLogin'))->assertRedirect();

    expect(auth()->check())->toBeTrue();
});
