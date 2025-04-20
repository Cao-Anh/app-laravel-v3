<?php

namespace App\Http\Requests;

use App\Models\User;

class StoreUserRequest extends BaseUserRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    public function rules(): array
    {
        return array_merge($this->baseRules(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5|max:9|regex:/[A-Z]/|confirmed',
        ]);
    }
}
