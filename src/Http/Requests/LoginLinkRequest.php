<?php

namespace Spatie\LoginLink\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginLinkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => '',
            'email' => 'email',
            'user_attributes' => 'json',
            'redirectUrl' => 'string',
        ];
    }

    public function userAttributes(): array
    {
        return json_decode($this->user_attributes, true) ?? [];
    }
}
