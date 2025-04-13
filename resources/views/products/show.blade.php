@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Thông Tin Sản Phẩm</h2>

        <table>
            <tr>
                <th>Tên sản phẩm:</th>
                <td>{{ $product->name }}</td>
            </tr>
            <tr>
                <th>Hinh anh:</th>
                <td>
                    @if (!empty($product->image))
                        <img src="{{ asset($product->image) }}" alt="Product Image" style="max-width: 150px; height: auto;">
                    @else
                        <p>Không có hình ảnh</p>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Số lượng:</th>
                <td>{{ $product->quantity }}</td>
            </tr>
            <tr>
                <th>Giá:</th>
                <td>${{ number_format($product->price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Mô tả:</th>
                <td>{{ $product->description }}</td>
            </tr>
        </table>
        @can('update', auth()->user())
            <button onclick="window.location.href='{{ route('products.edit', $product->id) }}'">Chỉnh sửa</button>
        @endcan

    </div>
@endsection
