@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Thông Tin Người Dùng</h2>

        <table>
            <tr>
                <th>Username:</th>
                <td>{{ $user->username }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Tổng tiền mua</th>
                <td>${{ $user->total_spent ?? 0 }}</td>
            </tr>
        </table>
        @can('update', auth()->user())
            <button class="btn btn-primary btn-sm" onclick="window.location.href='{{ route('users.edit', $user->id) }}'">Chỉnh sửa</button>
        @endcan
        <a href="{{ route('users.purchase_history', $user->id) }}" class="text-blue-500 hover:underline">
            Xem lịch sử mua hàng
        </a>
    </div>
@endsection
