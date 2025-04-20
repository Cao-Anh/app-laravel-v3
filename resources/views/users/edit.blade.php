@extends('layouts.auth')

@section('content')
    <div class="container">
        <h2>Chỉnh sửa người dùng</h2>

        @if (session('success'))
            <div style="color: green">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('users.update', $user->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <label for="username">Người dùng</label>
            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required>
            @error('username')
                <small>{{ $message }}</small>
            @enderror

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <small>{{ $message }}</small>
            @enderror

            <div class="button-container" style="margin-top: 10px;">
                <button onclick="window.history.back();" type="button">Quay lại</button>
                <button type="submit">Cập nhật</button>
            </div>
        </form>
    </div>
@endsection
