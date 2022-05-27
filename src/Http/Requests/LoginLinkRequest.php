<?php

namespace Spatie\LoginLink\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginLinkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => '',
            'email' => '',
            'user_attributes' => '',
            'redirectUrl' => '',
        ];
    }

    public function userAttributes(): array
    {
        return json_decode($this->user_attributes, true) ?? [];
    }
}
