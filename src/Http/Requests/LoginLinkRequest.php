<?php

namespace Spatie\LoginLink\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginLinkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => '',
            'email' => 'email',
            'userAttributes' => 'array',
            'redirectUrl' => 'string',
        ];
    }
}
