{{-- @extends('layouts.app')

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
        <!-- Buy Button -->
        <button class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#buyModal">
            Mua Ngay
        </button>


    </div>
    <!-- Buy Modal -->
    <div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('orders.buy') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="buyModalLabel">Mua {{ $product->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p><strong>Giá:</strong> ${{ number_format($product->price, 0, ',', '.') }}</p>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Số lượng:</label>
                            <input type="number" class="form-control" name="quantity" id="quantity" value="1"
                                min="1" max="{{ $product->quantity }}" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <!-- Add to Cart (JS/AJAX or separate route) -->
                       

                        <!-- Buy Now (form submits to route) -->
                        <button type="submit" class="btn btn-success">Mua ngay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value;

            fetch("{{ route('orders.add') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert('Đã thêm vào giỏ hàng!');
                    // You can also close the modal or redirect here
                })
                .catch(error => {
                    console.error(error);
                    alert('Có lỗi xảy ra khi thêm vào giỏ.');
                });
        }
    </script>
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $product->name }}</h2>
    <img src="{{ asset($product->image) }}" alt="Product Image" style="max-width:150px;">
    <p>Giá: ${{ number_format($product->price) }}</p>
    <p>Còn lại: {{ $product->quantity }}</p>
    <p>{{ $product->description }}</p>

    <!-- Buy Now Form -->
    <form action="{{ route('orders.buy') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="mb-3">
            <label>Số lượng:</label>
            <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}" required>
        </div>

        <div class="mb-3">
            <label>Địa chỉ giao hàng:</label>
            <input type="text" name="address" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Mua ngay</button>
    </form>
</div>
@endsection
