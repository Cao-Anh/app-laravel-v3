@extends('layouts.auth')

@section('content')
    <div class="container">
        <h2>Chỉnh sửa sản phẩm</h2>

        @if (session('success'))
            <div style="color: green">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="name">Tên sản phẩm</label>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
            @error('name')
                <small>{{ $message }}</small>
            @enderror

            <label for="image">Hình ảnh</label>
            <input type="file" id="image" name="image" accept="image/*">
            @error('image')
                <small>{{ $message }}</small>
            @enderror

            <label for="quantity">Số lượng</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
            @error('quantity')
                <small>{{ $message }}</small>
            @enderror

            <label for="price">Giá</label>
            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" required>
            @error('price')
                <small>{{ $message }}</small>
            @enderror

            <label for="description">Mô tả</label>
            <textarea id="description" name="description">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <small>{{ $message }}</small>
            @enderror

            <div class="button-container" style="margin-top: 10px;">
                <button onclick="window.history.back();">Quay lại</button>
                <button type="submit">Cập nhật</button>
            </div>
        </form>
    </div>
@endsection
