
@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row g-5">
        <!-- Product Image -->
        <div class="col-md-5 text-center">
            <img src="{{ asset($product->image) }}" alt="Product Image" class="img-fluid rounded shadow" style="max-height: 400px; object-fit: contain;">
        </div>

        <!-- Product Info & Form -->
        <div class="col-md-7">
            <h2 class="mb-3">{{ $product->name }}</h2>

            <p class="fs-4 text-danger fw-bold mb-1">
                ${{ number_format($product->price, 0, ',', '.') }}
            </p>
            <p class="text-muted mb-3">Còn lại: {{ $product->quantity }} sản phẩm</p>

            <p class="mb-4" style="line-height: 1.7;">{{ $product->description }}</p>

            <!-- Buy Now Form -->
            <form action="{{ route('orders.buy') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="mb-3">
                    <label class="form-label">Số lượng</label>
                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->quantity }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Địa chỉ giao hàng</label>
                    <input type="text" name="address" class="form-control" placeholder="Nhập địa chỉ của bạn" required>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2">🚀 Mua ngay</button>
            </form>
        </div>
    </div>
</div>

@endsection
