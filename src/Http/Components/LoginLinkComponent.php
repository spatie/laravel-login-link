<?php

namespace Spatie\LoginLink\Http\Components;

use Illuminate\View\Component;

class LoginLinkComponent extends Component
{
    public function __construct(
        public ?string $key = null,
        public ?string $email = null,
        public array $userAttributes = [],
        public string $label = 'Login',
    ) {
    }

    public function render()
    {
        return view('login-link::loginLink');
    }
}
