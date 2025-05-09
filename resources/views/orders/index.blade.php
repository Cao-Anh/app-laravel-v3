@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Quản lý đơn hàng</h1>

        <form method="POST" action="{{ route('orders.deleteInvalid') }}"
            onsubmit="return confirm('Xác nhận xóa tất cả đơn hàng không hợp lệ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger mb-3">Xóa đơn hàng không hợp lệ</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Người mua</th>
                    <th>Tổng tiền</th>
                    <th>Địa chỉ</th>
                    <th>Sản phẩm</th>
                    <th>Số Lượng</th>
                    <th>Giá</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user?->username ?? 'Không tồn tại' }}</td>
                        <td>${{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>{{ $order->address }}</td>
                        <td>
                            <ul class="">
                                @foreach ($order->orderDetails as $detail)
                                    <li> {{ $detail->product->name }} </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <ul class="">
                                @foreach ($order->orderDetails as $detail)
                                    <li> {{ $detail->quantity }} </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <ul class="">
                                @foreach ($order->orderDetails as $detail)
                                    <li> ${{ $detail->product->price }} </li>
                                @endforeach
                            </ul>
                        </td>



                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>
                            <form method="POST" action="{{ route('orders.destroy', $order->id) }}"
                                onsubmit="return confirm('Xác nhận xóa đơn hàng này?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $orders->links() }}
    </div>
@endsection
