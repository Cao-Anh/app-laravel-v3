<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $products = Product::commonSearch($search,$minPrice,$maxPrice,$startDate,$endDate)
            ->withSum('orderDetails as total_quantity', 'quantity')
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return view('products.index', compact('products'))->with('routeName', Route::currentRouteName());
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function create()
    {
        Gate::authorize('create', Product::class);

        return view('products.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Product::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'require|string',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;


        if ($request->hasFile('image')) {
            // dd($request->file('photo'));
            $file = $request->file('image');

            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(public_path('images'), $imageName);
            $imageUrl = 'images/' . $imageName;

            $product->image = $imageUrl;
        }
        $product->save();
        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công.');
    }

    public function edit($id)
    {
        Gate::authorize('update', Product::class);

        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('update', Product::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string',
        ]);
        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            // dd($request->file('photo'));
            $file = $request->file('image');

            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(public_path('images'), $imageName);
            $imageUrl = 'images/' . $imageName;

            $product->image = $imageUrl;
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
        ]);

        $product->save();
        return redirect()->route('products.show', $id)->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Request $request, $id)
    {
        Gate::authorize('delete', Product::class);

        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Xóa thành công.');
    }

    public function getMostPurchasedProducts(Request $request)
    {
        $search = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $products = Product::commonSearch($search,$minPrice,$maxPrice,$startDate,$endDate)
            ->withSum('orderDetails as total_quantity', 'quantity')
            ->orderByDesc('total_quantity')
            ->paginate(10)
            ->appends($request->query());


        return view('products.index', compact('products'))->with('routeName', Route::currentRouteName());
    }
    public function getLeastPurchasedProducts(Request $request)
    {
        $search = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $products = Product::commonSearch($search,$minPrice,$maxPrice,$startDate,$endDate)
            ->withSum('orderDetails as total_quantity', 'quantity')
            ->orderBy('total_quantity')
            ->paginate(10)
            ->appends($request->query());

        return view('products.index', compact('products'))->with('routeName', Route::currentRouteName());
    }
    public function sortByNameAsc(Request $request)
    {
        $search = $request->input('search');

        $search = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $products = Product::commonSearch($search,$minPrice,$maxPrice,$startDate,$endDate)
            ->withSum('orderDetails as total_quantity', 'quantity')
            ->orderBy('name')
            ->appends($request->query())
            ->paginate(10);

        return view('products.index', compact('products'))->with('routeName', Route::currentRouteName());
    }

    public function sortByNameDesc(Request $request)
    {
        $search = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $products = Product::commonSearch($search,$minPrice,$maxPrice,$startDate,$endDate)
            ->withSum('orderDetails as total_quantity', 'quantity')
            ->orderByDesc('name')
            ->appends($request->query())
            ->paginate(10);

        return view('products.index', compact('products'))->with('routeName', Route::currentRouteName());
    }
}
