<?php

namespace Spatie\LoginLink;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LoginLink\Http\Components\LoginLinkComponent;

class LoginLinkServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-login-link')
            ->hasConfigFile()
            ->hasViews();

        Blade::component(LoginLinkComponent::class, 'login-link');
    }

    public function bootingPackage()
    {
        $controller = config('login-link.login_link_controller');

        Route::post('laravel-login-link-login', $controller)
            ->name('loginLinkLogin')
            ->middleware(config('login-link.middleware'));
    }
}
