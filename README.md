
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Quickly login to your local environment

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-login-link.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-login-link)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-login-link/run-tests?label=tests)](https://github.com/spatie/laravel-login-link/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-login-link/Check%20&%20fix%20styling?label=code%20style)](https://github.com/spatie/laravel-login-link/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-login-link.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-login-link)

**THIS PACKAGE IS IN DEVELOPMENT, DO NOT USE IT (YET)**

When developing an app that has an admin section (or any non-public section), you'll likely seed test users to login. In large teams that work on many different apps it can be cumbersome to keep track of the right credentials. Is the user account "freek@spatie.be", or "user@spatie.be", or even "admin@spatie.be" Is that password "password", or "secret", or something is else?

This package solves that problem by offering a component that will render a login link. When clicked, that link will log you in.

In your login view, you can add the `x-login-link` component to show the login link.

```blade
@env('local')
    <div class="space-y-2">
        <x-login-link email="admin@spatie.be" label="Login as admin"/>
        <x-login-link email="user@spatie.be" label="Login as regular user"/>
    </div>
@endenv
```

<img style="width: 500px" alt="screenshot" src="https://github.com/spatie/laravel-login-link/blob/main/docs/login.png?raw=true" />

It is meant for local development, and probably shouldn't be used in any public reachable environment.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-login-link.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-login-link)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-login-link
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-login-link-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-login-link-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-login-link-views"
```

## Usage

```php
$LoginLink = new Spatie\LoginLink();
echo $LoginLink->echoPhrase('Hello, Spatie!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
