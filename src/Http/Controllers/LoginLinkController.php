<?php

namespace Spatie\LoginLink\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Spatie\LoginLink\Exceptions\DidNotFindUserToLogIn;
use Spatie\LoginLink\Exceptions\InvalidUserClass;
use Spatie\LoginLink\Exceptions\NotAllowedInCurrentEnvironment;
use Spatie\LoginLink\Exceptions\NotAllowedInCurrentHost;
use Spatie\LoginLink\Http\Requests\LoginLinkRequest;

class LoginLinkController
{
    public function __invoke(LoginLinkRequest $request)
    {
        $this->ensureAllowedEnvironment();

        $this->ensureAllowedHost($request);

        $authenticatable = $this->getAuthenticatable($request);

        $this->performLogin($request->guard, $authenticatable);

        $redirectUrl = $this->getRedirectUrl($request);

        return redirect()->to($redirectUrl);
    }

    protected function ensureAllowedEnvironment(): void
    {
        $allowedEnvironments = config('login-link.allowed_environments');

        if (! app()->environment($allowedEnvironments)) {
            throw NotAllowedInCurrentEnvironment::make($allowedEnvironments);
        }
    }

    protected function ensureAllowedHost(LoginLinkRequest $request): void
    {
        if ($this->getUserConfig('login-link.allowed_hosts') === null) {
            return;
        }

        $allowedHosts = config('login-link.allowed_hosts');

        $currentHost = $request->getHost();

        if (! in_array($currentHost, $allowedHosts)) {
            throw NotAllowedInCurrentHost::make($currentHost, $allowedHosts);
        }
    }

    protected function getAuthenticatable(LoginLinkRequest $request): Authenticatable
    {
        $attributes = $this->getUserAttributes($request);

        $authenticatableClass = $this->getAuthenticatableClass($request);

        $user = $authenticatableClass::query()
            ->when(count($attributes), fn (Builder $query) => $query->where($attributes))
            ->first();

        if ($user) {
            return $user;
        }

        if (! config('login-link.automatically_create_missing_users')) {
            throw DidNotFindUserToLogIn::make();
        }

        return $this->createUser($authenticatableClass, $attributes);
    }

    protected function performLogin(?string $guard, Authenticatable $authenticatable): void
    {
        auth($guard)->login($authenticatable);
    }

    protected function getUserAttributes(LoginLinkRequest $request): array
    {
        $attributes = $request->userAttributes();

        if ($identifier = $this->getAuthenticatableIdentifier($request)) {
            $attributes = array_merge([
                $identifier['attribute'] => $identifier['value'],
            ], $attributes);
        }

        return $attributes;
    }

    protected function getAuthenticatableIdentifier(LoginLinkRequest $request): ?array
    {
        if ($request->key) {
            $userClass = new ($this->getAuthenticatableClass($request));

            return [
                'attribute' => ($userClass)->getKeyName(),
                'value' => $request->key,
            ];
        }

        if ($request->email) {
            return [
                'attribute' => 'email',
                'value' => $request->email,
            ];
        }

        return null;
    }

    protected function getAuthenticatableClass(LoginLinkRequest $request): string
    {
        $guard = $request->guard;
        $provider = $guard === null
            ? config('auth.guards.web.provider')
            : config("auth.guards.{$guard}.provider");

        return $this->getUserModel($request)
            ?? config('login-link.user_model')
            ?? config("auth.providers.{$provider}.model")
            ?? throw InvalidUserClass::notFound();
    }

    protected function createUser(string $authenticatableClass, array $attributes): Authenticatable
    {
        return $authenticatableClass::factory()->create($attributes);
    }

    protected function getRedirectUrl(LoginLinkRequest $request): string
    {
        if ($request->redirect_url) {
            return $request->redirect_url;
        }

        if ($routeName = config('login-link.redirect_route_name')) {
            return route($routeName);
        }

        return redirect()->intended()->getTargetUrl();
    }

    private function getUserModel(LoginLinkRequest $request): ?string
    {
        $class = $request->user_model;

        if ($class === null) {
            return null;
        }

        if (class_exists($class) && is_subclass_of($class, Authenticatable::class)) {
            return $class;
        }

        return null;
    }

    protected function getUserConfig($key)
    {
        $parts = explode('.', $key);

        $file = array_shift($parts);

        $configPath = config_path($file.'.php');

        if (! file_exists($configPath)) {
            return config($key);
        }

        $configuration = require $configPath;

        return Arr::get($configuration, $key);
    }
}
