<?php

namespace Spatie\LoginLink\Tests\TestSupport;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LoginLink\LoginLinkServiceProvider;
use Spatie\LoginLink\Tests\TestSupport\Models\User;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('login-link.user_model', User::class);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Spatie\\LoginLink\\Tests\\TestSupport\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        Route::get('/', fn () => 'this is the home page');
    }

    protected function getPackageProviders($app)
    {
        return [
            LoginLinkServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');

            $table->timestamps();
        });
    }
}
