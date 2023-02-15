# Quickly login to your local environment

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-login-link.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-login-link)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-login-link/run-tests?label=tests)](https://github.com/spatie/laravel-login-link/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-login-link/Check%20&%20fix%20styling?label=code%20style)](https://github.com/spatie/laravel-login-link/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-login-link.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-login-link)

When developing an app that has an admin section (or any non-public section), you'll likely seed test users to login. In large teams that work on many different apps it can be cumbersome to keep track of the right credentials. Is the user account "yourname@company.com", or "test@company.com", or even "admin@company.com"? Is that password "password", or "secret", or something is else? How do I login with a user that has a different role?

This package solves that problem by offering a component that will render a login link. When clicked, that link will log you in.

In your login view, you can add the `x-login-link` component to show the login link. The `@env('local')` will make sure that the links are only rendered in the local environment.

```blade
@env('local')
    <div class="space-y-2">
        <x-login-link email="admin@spatie.be" label="Login as admin"/>
        <x-login-link email="user@spatie.be" label="Login as regular user"/>
    </div>
@endenv
```

Here's what that might look like in the browser:

<img style="width: 500px" alt="screenshot" src="https://github.com/spatie/laravel-login-link/blob/main/docs/login.png?raw=true" />

It is meant for local development, and probably shouldn't be used in any publicly reachable environment.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-login-link.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-login-link)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer. 

```bash
composer require spatie/laravel-login-link
```

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --tag="login-link-config"
```

This is the contents of the published config file:

```php
use Spatie\LoginLink\Http\Controllers\LoginLinkController;

return [
    /*
     * Login links will only work in these environments. In all
     * other environments, an exception will be thrown.
     */
    'allowed_environments' => ['local'],

    /*
     * The package will automatically create a user model when trying
     * to log in a user that doesn't exist.
     */
    'automatically_create_missing_users' => true,

    /*
     * The user model that should be logged in. If this is set to `null`
     * we'll take a look at the model used for the `users`
     * provider in config/auth.php
     */
    'user_model' => null,

    /*
     * After a login link is clicked, we'll redirect the user to this route.
     * If it is set to `null` , we'll redirect to `/`.
     */
    'redirect_route_name' => null,

    /*
     * The package will register a route that points to this controller. To have fine
     * grained control over what happens when a login link is clicked, you can
     * override this class.
     */
    'login_link_controller' => LoginLinkController::class,

    /*
     * This middleware will be applied on the route
     * that logs in a user via a link.
     */
    'middleware' => ['web'],
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="login-link-views"
```

## Usage

To render a login link, simply add the `x-login-link` Blade component to your view. We highly recommend to only render it in the `local` environment.

```blade
@env('local')
    <x-login-link />
@endenv
```

This component will render a link that, when clicked, will log you in. By default, it will redirect you to  `/`, but you can customize that by specifying a route name in the `redirect_route_name` of the `login-link` config file.

You can also specify the redirect URL on the component itself:

```blade
<x-login-link redirect-url="{{ route('dashboard') }}"  />
```

### Specifying the user model to log in

By default, it will use the user model class that is specified in the `providers.users.model` key of the `auth` config file. To override this, you can set the `user_model` of the `login-link` config file to the class name of your user model.

The package will log in the first user in the table. You customize that by passing an `email` attribute. The user with that mail address will be logged in.

```blade
<x-login-link email="admin@example.com"  />
```

Alternatively, you can specify the primary key of the user (in most cases this will be the `id`)

```blade
<x-login-link key="123"  />
```

You can also specify the attributes of the user that needs to be logged in.

```blade
<x-login-link :user-attributes="['role' => 'admin']"  />
```

### Customizing the login link

By default, the package will display "Developer login" as the text of the login link. You can customize that by passing a `label` attribute.

```blade
<x-login-link label="Click here to log in">
```

A login link will have the [Tailwind](https://tailwindcss.com) class `underline` by default. To customize that, you can pass any css class that you want to the class property. These classes will override the `underline` default.

Here's how you can create a red, underlined link (when using Tailwind CSS).

```blade
<x-login-link class="underline text-red-500">
```

### Specifying the login guard

By default, the package will use the default guard. You can specify another guard.

```blade
<x-login-link guard="admin">
```

### Automatic user creation

If the user that needs to be logged in does not exist, the package will use the factory of your user model to create the user, and log that new user in.

If you don't want this behaviour, set `automatically_create_missing_users` in the `local-link` config file to `false`.

### Usage with Vue and InertiaJS

The package has a built-in component to support Vue and InertiaJS. The props are the same of blade component.

Edit the `HandleInertiaRequests` middleware like so:
```php
public function share(Request $request): array
{
    return array_merge(parent::share($request), [
        'environment' => app()->environment(),
        // ...
    ]);
}
```

So, if you need to show the button only in your local environment, use the component like so:

```vue
import LoginLink from '@/../../vendor/spatie/laravel-login-link/resources/js/login-link.vue';

<LoginLink v-if="$page.props.environment == 'local'" />

// or

<LoginLink v-if="$page.props.environment == 'local'" label="Login as user@example.com" class="pb-3 text-red-500" :redirect-url="route('dashboard')" />
```

### Usage with React / Js / ...

The package comes with Vue support only. When you use any other JS front end framework to render your views, you can still make use of the package.

You should send a `POST` request to `/laravel-login-link-login`. If you don't give it any payload, then it will log in the first user in your users table. If there is no user, it will be created.

Optionally, you can post any of these payload fields. The functionality of these payloads fields match those of the attributes that you can pass to `x-login-link` component.

- `email`: attempt to log in the user with the given email address
- `key`: attempt to log in the user with the given key (in most cases the `id` of the users)
- `redirect_url`: to which URL should we redirect after logging in
- `user_attributes`: an array containing the attributes that the user that will be logged in needs to have.

Since this is a POST request, make sure to pass a CSRF token as well.

### Usage in other environments

Out of the box, the login link will only work in a local environment. If you want to use it other environments, set the `allowed_environments` key of the `login-link` config file to the names of those environments.

Beware however, that you should never display login links in any environment that is publicly reachable, as it will allow anyone to log in.

## How the package works under the hood

[Povilas Korop](https://twitter.com/povilaskorop) of [Laravel Daily](https://laraveldaily.com) made [a nice video on the internals of the package](https://www.youtube.com/watch?v=TN0NKHVeWGc).

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
