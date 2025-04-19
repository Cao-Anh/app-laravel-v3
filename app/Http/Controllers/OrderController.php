<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index()
    {

        // $orders = Order::with('user')->orderByDesc('created_at')->paginate(10);
        // return view('orders.index', compact('orders'));
        $orders = Order::with(['user', 'orderDetails.product'])
            ->orderByDesc('created_at')
            ->paginate(10);



        // $orders = Order::with('user')->orders()
        //     ->with(['orderDetails.product'])
        //     ->orderByDesc('created_at')
        //     ->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $orders = Order::with(['user', 'orderDetails.product'])
        ->where('user_id', $id)
        ->orderByDesc('created_at')
        ->paginate(10);
        $orders = Order::with('user')->where('user_id', $id)->orderByDesc('created_at')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function destroy($id)
    {
        Gate::authorize('delete', Product::class);
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Đã xóa đơn hàng.');
    }

    public function deleteInvalid()
    {
        Gate::authorize('delete', Product::class);
        $deleted = Order::whereNull('user_id')->delete();

        return redirect()->route('orders.index')->with('success', "Đã xóa $deleted đơn hàng không hợp lệ.");
    }
    public function buy(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'address' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;

            if ($product->quantity < $quantity) {
                return back()->with('error', 'Không đủ hàng trong kho.');
            }

            $total = $product->price * $quantity;

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'address' => $request->address,
            ]);

            $order->products()->attach($product->id, [
                'quantity' => $quantity,
            ]);

            // Optional: reduce product quantity
            $product->decrement('quantity', $quantity);

            DB::commit();

            return redirect()->route('products.show', $product->id)->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Đã có lỗi: ' . $e->getMessage());
        }
    }
}
