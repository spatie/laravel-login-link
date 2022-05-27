<?php

namespace Spatie\LoginLink;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LoginLink\Http\Components\LoginLinkComponent;
use Spatie\LoginLink\Http\Controllers\LoginLinkController;

class LoginLinkServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-login-link')
            ->hasConfigFile()
            ->hasViews();

        Route::post('laravel-login-link-login', LoginLinkController::class)
            ->name('loginLinkLogin')
            ->middleware(config('login-link.middleware'));

        Blade::component(LoginLinkComponent::class, 'login-link');
    }
}
