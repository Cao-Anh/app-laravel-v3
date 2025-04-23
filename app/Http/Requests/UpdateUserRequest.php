<?php

namespace App\Http\Requests;

class UpdateUserRequest extends BaseUserRequest
{
    // public function authorize(): bool
    // {
    //     return $this->user()->can('update', $this->route('user'));
    // }

    public function rules(): array
    {
        return array_merge($this->baseRules(), [
            'email' => 'required|email|unique:users,email,' . $this->route('user')->id,
            'password' => 'sometimes|nullable|string|min:5|max:9|regex:/[A-Z]/|confirmed',
        ]);
    }
}
