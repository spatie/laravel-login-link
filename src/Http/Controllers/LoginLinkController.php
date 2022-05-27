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

        $redirectUrl = $request->redirectUrl
            ?? route(config('login-link.redirect_route_name'))
            ?? throw new \Exception(); // to do: implement

        return redirect()->url($request->redirectUrl ?? route(config('login-link.redirect_route_name')));
    }

    protected function getAuthenticatable(LoginLinkRequest $request): Authenticatable
    {
        $identifier = $this->getAuthenticatableIdentifier($request);

        $userClass = $this->getAuthenticatableClass();

        if ($identifier) {
            $user = $userClass::where($identifier['attribute'], $identifier['value'])->first();

            if ($user) {
                return $user;
            }
        }

        $attributes = array_merge([

        ], $attributes);

        return $this->createUser($attributes);
    }

    public function getAuthenticatableIdentifier(LoginLinkRequest $request): ?array
    {
        if ($request->key) {
            $userClass = new $this->getAuthenticatableClass();

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

        return config('login-link.default_user_model')
            ?? config('auth.providers.user.model')
            ?? throw InvalidUserClass::notFound();
    }

    protected function createUser(string $authenticatableClass, array $attributes): Authenticatable
    {
        return $authenticatableClass::factory()->create($attributes);
    }
}
