<?php

namespace Spatie\LocalLogin;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LocalLogin\Commands\LocalLoginCommand;

class LocalLoginServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-local-login')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-local-login_table')
            ->hasCommand(LocalLoginCommand::class);
    }
}
