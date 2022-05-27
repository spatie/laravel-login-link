<?php

use function Pest\Laravel\post;
use Spatie\LoginLink\Tests\TestSupport\Models\User;

it('will create and login a user', function () {
    post(route('loginLinkLogin'))->assertRedirect();

    expectUserToBeLoggedIn();
    expect(User::count())->toBe(1);
});

it('will not create a new user if one already exists', function () {
    post(route('loginLinkLogin'))->assertRedirect();
    post(route('loginLinkLogin'))->assertRedirect();

    expectUserToBeLoggedIn();
    expect(User::count())->toBe(1);
});

it('can create a user with specific email', function () {
    $data = ['email' => 'freek@spatie.be'];

    post(route('loginLinkLogin'), $data)->assertRedirect();

    expectUserToBeLoggedIn(['email' => 'freek@spatie.be']);
});

it('can login an existing user with a specific email', function () {
    User::factory()->create(['email' => 'freek@spatie.be']);
    $data = ['email' => 'freek@spatie.be'];

    post(route('loginLinkLogin'), $data)->assertRedirect();

    expectUserToBeLoggedIn(['email' => 'freek@spatie.be']);
    expect(User::count())->toBe(1);
});

it('can create a user with specific id', function () {
    $data = ['key' => '123'];

    post(route('loginLinkLogin'), $data)->assertRedirect();

    expectUserToBeLoggedIn(['id' => 123]);
});

it('can login an existing user with a specific id', function () {
    User::factory()->create(['id' => 123]);
    $data = ['key' => 123];

    post(route('loginLinkLogin'), $data)->assertRedirect();

    expectUserToBeLoggedIn(['id' => 123]);
    expect(User::count())->toBe(1);
});

it('can redirect to a specific url', function () {
    $data['redirectUrl'] = 'custom-url';

    post(route('loginLinkLogin'), $data)->assertRedirect('custom-url');

    expectUserToBeLoggedIn();
});

it('will redirect to the configured route name by default', function () {
    config()->set('login-link.redirect_route_name', 'customUrlRouteName');
    $data = ['redirectUrl', route('customUrlRouteName')];

    post(route('loginLinkLogin'), $data)->assertRedirect(route('customUrlRouteName'));

    expectUserToBeLoggedIn();
});

it('will throw an exception when a user model is not configured', function () {
    config()->set('login-link.user_model', null);

    post(route('loginLinkLogin'))->assertStatus(500);
});
