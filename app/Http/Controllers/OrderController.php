<?php

namespace App\Http\Controllers;

use App\Book;
use App\Category;
use App\Collection;
use App\FooterMenu;
use App\Order;
use App\Providers\LiqpayServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LiqPay;

class OrderController extends Controller
{
    public function createOrder(Request $request, $id)
    {
       if(Auth::user() && isset($id) && !empty($id)){
           $book = Book::find($id);
           $cats = Category::all();
           $cols = Collection::all();
           $foot = FooterMenu::all();

           $order = new Order();
           $order->user_id = Auth::user()->id;
           $order->book_id = $id;
           $order->ip = $request->ip();
           $order->description = 'Покупка книги '. $book->name;
           $order->result = 'create';
           $order->save();

           if(isset($order) && isset($book) && !empty($book)){
               session()->put('order_id', $order->id);
               $liqpay = new LiqPay(env('LIQPAY_PUBLIC_KEY'), env('LIQPAY_PRIVATE_KEY'));
               $html = $liqpay->cnb_form(array(
                   'action'         => 'pay',
                   'amount'         => $book->price,
                   'currency'       => 'RUB',
                   'description'    => $order->description,
                   'order_id'       => $order->id, // $order->payment_id
                   'version'        => '3',
//                   'sandbox'        => '1',
               ));

               return view('pages.liqpay',[
                   'form'=>$html,
                   'cols'=>$cols,
                   'cats'=>$cats,
                   'book'=>$book,
                   'foot'=>$foot
               ]);
           }
       }
    }

    public function acceptOrder(Request $request)
    {
        $sess = session()->get('order_id');

        if(isset($sess) && !empty($sess))
        {
            $liqpay = new LiqPay(env('LIQPAY_PUBLIC_KEY'), env('LIQPAY_PRIVATE_KEY'));
            $res = $liqpay->api("request", array(
                'action'        => 'status',
                'version'       => '3',
                'order_id'      => $sess
            ));

            if(isset($res) && !empty($res)){
                $order = Order::find($res->order_id);
                $order->payment_id = $res->payment_id;
                $order->result = $res->result;
                $order->paytype = $res->paytype;
                $order->liqpay_order_id = $res->liqpay_order_id;
//                $order->description = $res->description;
//                $order->ip = $res->ip;
                $order->summ = $res->amount;
                $order->currency = $res->currency;
                $order->save();
                return redirect(route('getbook', ['id' => $order->book_id]));
            }

        }else{
            return redirect('/');
        }
    }
}
