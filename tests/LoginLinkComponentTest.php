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

    assertMatchesHtmlSnapshot($html);
});
