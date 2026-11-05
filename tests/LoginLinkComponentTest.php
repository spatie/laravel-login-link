<?php

use Illuminate\Support\Facades\Blade;

use function Spatie\Snapshots\assertMatchesHtmlSnapshot;

it('can render a login link', function () {
    $html = Blade::render('<x-login-link />');

    assertMatchesHtmlSnapshot($html);
});

it('can render a login link with a custom email', function () {
    $html = Blade::render('<x-login-link email="freek@spatie.be" />');

    assertMatchesHtmlSnapshot($html);
});

it('can render a login link with a custom key', function () {
    $html = Blade::render('<x-login-link key="123" />');

    assertMatchesHtmlSnapshot($html);
});

it('can render a login link with a custom label', function () {
    $html = Blade::render('<x-login-link label="My label" />');

    assertMatchesHtmlSnapshot($html);
});

it('can render a login link with custom user attributes', function () {
    $html = Blade::render('<x-login-link :user-attributes="[\'role\' => \'admin\']" />');

    $document = new DOMDocument();
    $document->loadHTML($html);
    $userAttributes = (new DOMXPath($document))->evaluate('string(//input[@name="user_attributes"]/@value)');

    expect(json_decode($userAttributes, true, 512, JSON_THROW_ON_ERROR))->toBe(['role' => 'admin']);
});

it('can render a login link with custom user attributes with a space from a variable', function () {
    $role = 'senior admin';
    $blade = <<<END
    <x-login-link :user-attributes="['role' => '$role']" />'
    END;
    $html = Blade::render($blade);

    $document = new DOMDocument();
    $document->loadHTML($html);
    $userAttributes = (new DOMXPath($document))->evaluate('string(//input[@name="user_attributes"]/@value)');

    expect(json_decode($userAttributes, true, 512, JSON_THROW_ON_ERROR))->toBe(['role' => 'senior admin']);
});

it('can render a login link with a specific redirect url', function () {
    $html = Blade::render('<x-login-link redirect-url="{{ route(\'customUrlRouteName\') }}" />');

    assertMatchesHtmlSnapshot($html);
});

it('can render a login link with a specific guard', function () {
    $html = Blade::render('<x-login-link guard="admin" />');

    assertMatchesHtmlSnapshot($html);
});

it('can render a login link with a custom user model', function () {
    $html = Blade::render('<x-login-link user_model="App\\Models\\Customer" />');

    assertMatchesHtmlSnapshot($html);
});

it('can render a login link with specific css classes', function () {
    $html = Blade::render('<x-login-link class="text-red-500" />');

    assertMatchesHtmlSnapshot($html);
});
