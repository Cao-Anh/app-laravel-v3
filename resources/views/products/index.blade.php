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
        <div class="container">
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach ($products as $product)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            @if (!empty($product->image))
                                <img src="{{ asset($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                                <div class="card-img-top bg-light d-flex justify-content-center align-items-center"
                                    style="height: 200px;">
                                    <span class="text-muted">Không có hình ảnh</span>
                                </div>
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text text-danger fw-bold">{{ number_format($product->price, 0, ',', '.') }}₫
                                </p>
                                <p class="card-text"><small class="text-muted">Còn lại: {{ $product->quantity }}</small>
                                </p>
                                <p class="card-text">{{ Str::limit($product->description, 60) }}</p>
                            </div>

                            <div class="card-footer d-flex flex-wrap justify-content-between gap-2">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-success btn-sm">Xem</a>

                                @can('update', auth()->user())
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">Sửa</a>
                                @endcan

                                @can('delete', auth()->user())
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <!-- Pagination -->
        {{ $products->links() }}
    </div>
@endsection
