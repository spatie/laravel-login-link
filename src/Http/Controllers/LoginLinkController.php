<?php

namespace Spatie\LoginLink\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\LoginLink\Exceptions\InvalidUserClass;
use Spatie\LoginLink\Exceptions\NotAllowedInCurrentEnvironment;
use Spatie\LoginLink\Http\Requests\LoginLinkRequest;

class LoginLinkController
{
    public function __invoke(LoginLinkRequest $request)
    {
        $this->ensureAllowedEnvironment();

        $authenticatable = $this->getAuthenticatable($request);

        $this->performLogin($authenticatable);

        $redirectUrl = $this->getRedirectUrl($request);

        return redirect()->to($redirectUrl);
    }

    protected function getAuthenticatable(LoginLinkRequest $request): Authenticatable
    {
        $identifier = $this->getAuthenticatableIdentifier($request);

        $authenticatableClass = $this->getAuthenticatableClass();

        if ($identifier) {
            $user = $authenticatableClass::where($identifier['attribute'], $identifier['value'])->first();

            if ($user) {
                return $user;
            }
        }

        if (! $identifier) {
            if ($user = $authenticatableClass::first()) {
                return $user;
            }
        }

        $attributes = $request->userAttributes ?? [];

        if ($identifier) {
            $attributes = array_merge([
                $identifier['attribute'] => $identifier['value'],
            ], $attributes);
        }

        return $this->createUser($authenticatableClass, $attributes);
    }

    public function getAuthenticatableIdentifier(LoginLinkRequest $request): ?array
    {
        if ($request->key) {
            $userClass = new ($this->getAuthenticatableClass());

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

    protected function getAuthenticatableClass(): string
    {
        return config('login-link.user_model')
            ?? config('auth.providers.users.model')
            ?? throw InvalidUserClass::notFound();
    }

    protected function createUser(string $authenticatableClass, array $attributes): Authenticatable
    {
        return $authenticatableClass::factory()->create($attributes);
    }

    protected function getRedirectUrl(LoginLinkRequest $request): string
    {
        if ($request->redirectUrl) {
            return $request->redirectUrl;
        }

        if ($routeName = config('login-link.redirect_route_name')) {
            return route($routeName);
        }

        return '/';
    }

    protected function ensureAllowedEnvironment(): void
    {
        $allowedEnvironments = config('login-link.allowed_environments');

        if (! app()->environment($allowedEnvironments)) {
            throw NotAllowedInCurrentEnvironment::make($allowedEnvironments);
        }
    }

    protected function performLogin(Authenticatable $authenticatable): void
    {
        auth()->login($authenticatable);
    }
}
