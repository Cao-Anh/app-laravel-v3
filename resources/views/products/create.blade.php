@extends('layouts.auth')

@section('content')
    <div class="container">
        <h2>Thêm sản phẩm mới</h2>

        @if (session('success'))
            <div style="color: green">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="name">Tên sản phẩm</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <small>{{ $message }}</small>
            @enderror

            <label for="image">Hình ảnh</label>
            <input type="file" id="image" name="image" accept="image/*">
            @error('image')
                <small>{{ $message }}</small>
            @enderror

            <label for="quantity">Số lượng</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
            @error('quantity')
                <small>{{ $message }}</small>
            @enderror

            <label for="price">Giá</label>
            <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" required>
            @error('price')
                <small>{{ $message }}</small>
            @enderror

            <label for="description">Mô tả</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
            @error('description')
                <small>{{ $message }}</small>
            @enderror

            <div class="button-container" style="margin-top: 10px;">
                <button onclick="window.history.back();" type="button">Quay lại</button>
                <button type="submit">Thêm</button>
            </div>
        </form>
    </div>
@endsection
