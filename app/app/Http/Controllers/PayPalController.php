<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\Payment as py;
use PayPal\Api\PaymentExecution;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Details;
use PayPal\Api\Payer;
use PayPal\Api\Transaction;
use PayPal\Api\Amount;
use PayPal\Api\ItemList;
use PayPal\Api\Item;
use PayPal\Api\RedirectUrls;
use Srmklive\PayPal\Services\ExpressCheckout;
use Srmklive\PayPal\Facades\PayPal;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Cart;
class PayPalController extends Controller
{
    public function payment($order_id)
    {
        return view('payment', compact('order_id'));
    }

    public function paymentpro($order_id)
    {
        $order = Order::findOrFail($order_id);

        $data = [];
        $data['items'] = [];
        $subtotal = 0;

        foreach ($order->orderItems as $item) {
            $itemTotal = $item->price * $item->quantity;
            $subtotal += $itemTotal;

            $data['items'][] = [
                'name'  => $item->product->name,
                'price' => number_format($item->price, 2, '.', ''), // تأكد من تنسيق السعر
                'desc'  => 'Quantity: ' . $item->quantity,
                'qty'   => $item->quantity,
            ];
        }

        // حساب المجموع النهائي بدقة
        $shipping = $order->shipping_cost ?? 0;
        $tax = $order->tax_amount ?? 0;
        $total = $subtotal + $shipping + $tax;

        // التأكد من تطابق القيم قبل الإرسال
        $total = number_format($total, 2, '.', '');
        $subtotal = number_format($subtotal, 2, '.', '');
        $shipping = number_format($shipping, 2, '.', '');
        $tax = number_format($tax, 2, '.', '');

        // إضافة تفاصيل الشحن والضرائب
        $data['invoice_id'] = $order->id;
        $data['invoice_description'] = "Order #{$order->id} Invoice";
        $data['return_url'] = route('payment.success', ['order_id' => $order->id]);
        $data['cancel_url'] = route('payment.cancel', ['order_id' => $order->id]);
        $data['total'] = $total;
        $data['custom'] = $order->id;

      
        $data['shipping'] = $shipping;
        $data['tax'] = $tax;
        $data['subtotal'] = $subtotal;

        $provider = new ExpressCheckout();
        $response = $provider->setExpressCheckout($data);
        $response = $provider->setExpressCheckout($data, true);
        
        if (!isset($response['paypal_link'])) {
            return back()->with('error', 'error PayPal.');
        }
        //dd($response);
        return redirect()->away($response['paypal_link']);
    }


    public function success(Request $request)
    {
        $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request->token);
       
       //dd($response);
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
           
            $order = Order::findOrFail($request->order_id);
            $payment = Payment::where('order_id', $order->id)->first();
            $payment->status = 'completed';
            $payment->save();
         
             Cart::where('user_id', auth()->id())->delete();

            return redirect()->route('orders.show', ['order' => $order->id])->with('success', 'تمت عملية الدفع بنجاح!');
        }

        return redirect()->route('checkout.index')->with('error', 'حدث خطأ أثناء معالجة الدفع.');
    }

    public function cancel(Request $request)
    {
       
        $order = Order::findOrFail($request->order_id);
        $payment = Payment::where('order_id', $order->id)->first();
        $payment->status = 'failed';
        $payment->save();

        return redirect()->route('checkout.index')->with('error', 'تم إلغاء عملية الدفع.');
    }
}