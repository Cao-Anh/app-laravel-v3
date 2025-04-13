@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Lịch sử mua hàng của {{ $user->username }}</h2>

    @forelse($orders as $order)
        <div class="border p-4 mb-4 rounded shadow">
            <p><strong>Mã đơn:</strong> {{ $order->id }}</p>
            <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Tổng tiền:</strong> ${{ number_format($order->total_amount) }} </p>

            <hr class="my-2">

            <h4 class="font-semibold">Sản phẩm:</h4>
            <ul class="list-disc ml-6">
                @foreach($order->orderDetails as $detail)
                    <li>
                        {{ $detail->product->name }} - SL: {{ $detail->quantity }} - 
                        Giá: ${{ number_format($detail->product->price) }} 
                    </li>
                @endforeach
            </ul>
        </div>
    @empty
        <p>Người dùng này chưa có đơn hàng nào.</p>
    @endforelse
@endsection
