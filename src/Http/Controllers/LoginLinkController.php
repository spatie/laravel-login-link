<?php

namespace Spatie\LoginLink\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User;
use Spatie\LoginLink\Exceptions\InvalidUserClass;
use Spatie\LoginLink\Http\Requests\LoginLinkRequest;

class LoginLinkController
{
    public function __invoke(LoginLinkRequest $request)
    {
        $authenticatable = $this->getAuthenticatable($request);

        auth()->login($authenticatable);

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
        (new User())->getKeyName();

        return config('login-link.user_model')
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
}
