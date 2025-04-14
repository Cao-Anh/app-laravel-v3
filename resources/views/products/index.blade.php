@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Danh sách sản phẩm</h1>

        <form method="GET" action="{{ route('products.index') }}" style="margin-bottom: 20px;">
            {{-- Hàng 1: Tìm kiếm + nút --}}
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên hoặc mô tả sản phẩm"
                    style="padding: 5px; width: 250px;">
                <button type="submit"
                    style="padding: 5px 10px; background-color: #3490dc; color: white; border: none; cursor: pointer;">
                    Tìm
                </button>
            </div>

            {{-- Hàng 2: Khoảng giá --}}
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <input type="number" name="min_price" class="form-control" placeholder="Giá thấp nhất"
                    value="{{ request('min_price') }}">
                <input type="number" name="max_price" class="form-control" placeholder="Giá cao nhất"
                    value="{{ request('max_price') }}">
            </div>

            {{-- Hàng 3: Khoảng ngày --}}
            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
        </form>

        {{-- <form action="{{ route('products.index') }}" method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="number" name="min_price" class="form-control" placeholder="Giá thấp nhất" value="{{ request('min_price') }}">
                </div>
                <div class="col-md-3">
                    <input type="number" name="max_price" class="form-control" placeholder="Giá cao nhất" value="{{ request('max_price') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </div>
        </form> --}}

        {{-- filter dropdown --}}
        <div style="display: flex; justify-content:left; position: relative; margin-bottom: 20px; align-items:">
            <button onclick="toggleFilterDropdown()"
                style="padding: 8px 12px; background-color: #6c757d; color: white; border: none; cursor: pointer;">
                Bộ lọc
            </button>

            <div id="userFilterDropdown"
                style="display: none; position: absolute; top: 110%; left: 0; background: white; border: 1px solid #ccc; box-shadow: 0 2px 5px rgba(0,0,0,0.15); z-index: 100; width: 280px;">
                <a href="{{ route('products.most_purchased') }}"
                    style="display: block; padding: 10px; text-decoration: none; color: black;">Sản phẩm được mua nhiều
                    nhất</a>
                <a href="{{ route('products.least_purchased') }}"
                    style="display: block; padding: 10px; text-decoration: none; color: black;">Sản phẩm được mua ít
                    nhất</a>
                <a href="{{ route('products.name_order_asc') }}"
                    style="display: block; padding: 10px; text-decoration: none; color: black;">Sắp xếp theo tên (a->z)</a>
                <a href="{{ route('products.name_order_desc') }}"
                    style="display: block; padding: 10px; text-decoration: none; color: black;">Sắp xếp theo tên (z->a)</a>

            </div>
        </div>

        <script>
            function toggleFilterDropdown() {
                const dropdown = document.getElementById('userFilterDropdown');
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            }

            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('userFilterDropdown');
                if (!event.target.closest('button') && !event.target.closest('#userFilterDropdown')) {
                    dropdown.style.display = 'none';
                }
            });
        </script>
        <table>
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Mô tả</th>
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
                        <td>${{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
                            <button class="index-button"
                                style="background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer;"
                                onclick="window.location.href='{{ route('products.show', $product->id) }}'">Xem</button>

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
