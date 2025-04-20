<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseUserRequest extends FormRequest
{
    protected function baseRules(): array
    {
        return [
            'username' => 'required|string|min:3|max:8',
            'email' => 'required|email',
            'roles' => 'array|exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Tên người dùng không được để trống.',
            'username.min' => 'Tên người dùng phải có ít nhất :min ký tự.',
            'username.max' => 'Tên người dùng không được vượt quá :max ký tự.',

            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được đăng ký.',

            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.max' => 'Mật khẩu không được vượt quá :max ký tự.',
            'password.regex' => 'Mật khẩu phải chứa ít nhất một chữ cái viết hoa.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',

            'roles.array' => 'Vai trò không hợp lệ.',
            'roles.exists' => 'Vai trò đã chọn không tồn tại.',
        ];
    }
}
