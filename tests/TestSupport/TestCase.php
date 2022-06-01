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
        config()->set('login-link.allowed_environments', ['testing']);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Spatie\\LoginLink\\Tests\\TestSupport\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LoginLinkServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $this
            ->setUpDatabase()
            ->setupRoutes();
    }

    protected function setUpDatabase(): self
    {
        config()->set('database.default', 'testing');

        config()->set('app.key', 'base64:LjpSHzPr1BBeuRWrlUcN2n2OWZ36o8+VpTLZdHcdG7Q=');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('role');

            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('role');

            $table->timestamps();
        });

        return $this;
    }

    protected function setupRoutes(): self
    {
        Route::get('/', fn () => 'this is the home page');
        Route::get('custom-url', fn () => 'custom page')->name('customUrlRouteName');

        return $this;
    }
}
