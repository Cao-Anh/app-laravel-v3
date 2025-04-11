@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Danh sách sản phẩm</h1>
        <table>
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Mô tả</th>
                    <th>Số lượt mua</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>
                            @if (!empty($product->image))
                                <img src="{{ asset($product->image) }}" alt="Product Image"
                                    style="max-width: 150px; height: auto;">
                            @else
                                <p>Không có hình ảnh</p>
                            @endif
                        </td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->total_quantity }}</td>

                        <td>
                            <button class="index-button"
                                style="background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer;"
                                onclick="window.location.href='{{ route('products.show', $product->id) }}'">Xem</button>

                            {{-- @if (auth()->check() && auth()->user()->role == 'admin')
                                <button class="index-button"
                                    style="background-color: blue; color: white; border: none; padding: 5px 10px; cursor: pointer;"
                                    onclick="window.location.href='{{ route('products.edit', $product->id) }}'">Sửa</button>

                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="index-button"
                                        style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</button>
                                </form>
                            @endif --}}
                            @can('update', auth()->user())
                            <button class="index-button"
                            style="background-color: blue; color: white; border: none; padding: 5px 10px; cursor: pointer;"
                            onclick="window.location.href='{{ route('products.edit', $product->id) }}'">Sửa</button>
                            @endcan
                            @can('delete', auth()->user())
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="index-button"
                                    style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        {{ $products->links() }}
    </div>
@endsection
