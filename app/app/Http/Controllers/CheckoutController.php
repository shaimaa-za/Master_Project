<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Shipping;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LowStockNotification;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cart ?? collect([]);
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $order = Order::latest()->first();

        return view('checkout.index', compact('cartItems', 'total', 'order'));
    }

    public function store(Request $request)
    {
        $shippingCosts = [
            'DHL_Express'   => 12,
            'DHL_Standard'  => 6,
            'FedEx_Express' => 15,
            'FedEx_Standard'=> 7,
        ];
        $shippingFee = $shippingCosts[$request->carrier] ?? 0;

        $address = new Address();
        $address->user_id = auth()->id();
        $address->full_name = $request->full_name;
        $address->phone = $request->phone;
        $address->address_line1 = $request->address_line1;
        $address->address_line2 = $request->address_line2;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->country = $request->country;
        $address->zip_code = $request->zip_code;
        $address->save();

        $cartItems = auth()->user()->cart ?? collect([]);

        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->back()->with('error', "  The requested quantity of the product is  {$item->product->name}  not available. : {$item->product->stock}");
            }
        }

        $totalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $totalPrice += $shippingFee;

        $order = new Order();
        $order->user_id = auth()->id();
        $order->total_price = $totalPrice;
        $trackingNumber = strtoupper('TRK' . uniqid());
        $order->save();
        
        foreach ($cartItems as $item) {
            $product = $item->product;
            $newStock = $product->stock - $item->quantity;

            $product->stock = $newStock;
            $product->save();

            if ($newStock < 3) {
                Notification::route('mail', env('ADMIN_EMAIL'))
                    ->notify(new LowStockNotification($product));
            }

            OrderItem::create([
                'order_id'  => $order->id,
                'product_id'=> $item->product->id,
                'quantity'  => $item->quantity,
                'price'     => $item->product->price,
                'subtotal'  => $item->product->price * $item->quantity,
            ]);
        }

        Shipping::create([
            'order_id'        => $order->id,
            'company_shipping'=> $request->carrier,
            'shipping_fee'    => $shippingFee,
            'tracking_number' => $order->tracking_number,
            'status'          => 'pending',
            'address_id'      => $address->id,
        ]);

        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->payment_method = $request->payment_method;
        $payment->status = 'pending';
        $payment->save();

        if ($request->payment_method == 'paypal') {
            return redirect()->route('payment', ['order_id' => $order->id]);
        }

        Cart::where('user_id', auth()->id())->delete();

        return redirect()->route('orders.show', ['order' => $order->id]);
    }
}
