<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index()
    {

        $orders = Order::with('user')->orderByDesc('created_at')->paginate(10);
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
}
