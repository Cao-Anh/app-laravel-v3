<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use PhpParser\Node\Expr\FuncCall;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
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
            'name'=>$request->name,
            'price'=>$request->price,
            'quantity'=>$request->quantity,
            'description'=>$request->description,
        ]);

        $product->save();
        return redirect()->route('products.show', $id)->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Request $request, $id)
    {   
       
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Xóa thành công.');
    }
}
