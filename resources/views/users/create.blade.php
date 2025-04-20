@extends('layouts.auth')

@section('content')
    <div class="container">
        <h2>Tạo người dùng mới</h2>

        @if (session('success'))
            <div style="color: green">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" novalidate>
            @csrf

            <label for="username">Người dùng</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required>
            @error('username')
                <small>{{ $message }}</small>
            @enderror

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <small>{{ $message }}</small>
            @enderror

            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>
            @error('password')
                @if (!Str::contains($message, 'confirmation'))
                    <small>{{ $message }}</small>
                @endif
            @enderror

            <label for="password_confirmation">Xác nhận mật khẩu</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
            @error('password')
                @if (str_contains($message, 'confirmation'))
                    <small>{{ $message }}</small>
                @endif
            @enderror
            <label for="roles">Vai trò</label>
            <div>
                @foreach ($roles as $role)
                    <label>
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}">
                        {{ $role->name }}
                    </label><br>
                @endforeach
            </div>
            @error('roles')
                <small>{{ $message }}</small>
            @enderror

            <div class="button-container" style="margin-top: 10px;">
                <button onclick="window.history.back();" type="button">Quay lại</button>
                <button type="submit">Tạo</button>
            </div>
        </form>
    </div>
@endsection
