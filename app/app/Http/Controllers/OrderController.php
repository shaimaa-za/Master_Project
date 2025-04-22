<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Shipping;


class OrderController extends Controller
{
    public function show($id)
    {
        $order = Order::with(['payment', 'shipping', 'shipping.address', 'orderItems.product'])->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                       ->with(['orderItems.product', 'shipping', 'payment'])
                       ->get();

        return view('orders.index', compact('orders'));
    }


    public function returnOrder($id)
    {
        $order = Order::with('payment', 'shipping')->findOrFail($id);
    
        // التحقق من طريقة الدفع
        if ($order->payment && $order->payment->payment_method !== 'cod') {
            return redirect()->back()->with('error', 'The payment method must be "COD" to request a return.');
        }
    
        // التحقق من حالة الشحن
        if ($order->shipping && $order->shipping->status !== 'pending') {
            return redirect()->back()->with('error', 'The shipping status must be "pending" to request a return.');
        }
    
        // التحقق من حالة الطلب
        if ($order->status === 'canceled' || $order->status === 'shipped' || $order->status === 'delivered') {
            return redirect()->back()->with('error', 'The order cannot be returned because it is either canceled, shipped, or delivered.');
        }
    
        // تحديث حالة الدفع إلى "failed"
        if ($order->payment) {
            $order->payment->status = 'failed'; 
            $order->payment->save();
        }
    
        // تحديث حالة الشحن إلى "canceled"
        if ($order->shipping) {
            $order->shipping->status = 'canceled';
            $order->shipping->save();
        }
    
        return back()->with('success', 'Order returned and shipping status updated to "canceled".');
    }
    
    
}
