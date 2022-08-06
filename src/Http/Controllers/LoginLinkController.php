<?php

namespace Spatie\LoginLink\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LoginLink\Exceptions\DidNotFindUserToLogIn;
use Spatie\LoginLink\Exceptions\InvalidUserClass;
use Spatie\LoginLink\Exceptions\NotAllowedInCurrentEnvironment;
use Spatie\LoginLink\Http\Requests\LoginLinkRequest;

class LoginLinkController
{
    public function __invoke(LoginLinkRequest $request)
    {
        $this->ensureAllowedEnvironment();

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

    protected function getAuthenticatable(LoginLinkRequest $request): Authenticatable
    {
        $attributes = $this->getUserAttributes($request);

        $authenticatableClass = $this->getAuthenticatableClass($request->guard);

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
            $userClass = new ($this->getAuthenticatableClass($request->guard));

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

    protected function getAuthenticatableClass(?string $guard): string
    {
        $provider = $guard === null
            ? config('auth.guards.web.provider')
            : config("auth.guards.{$guard}.provider");

        return config('login-link.user_model')
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
}
