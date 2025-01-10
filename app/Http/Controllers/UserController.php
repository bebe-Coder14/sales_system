<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){
        return view('user.index');
    }
    
    public function address()
    {
        $user_id = Auth::user()->id;
    
        // Fetch all addresses for the authenticated user
        $address = Address::where('user_id', $user_id)->get()->first();
    
        if ($address->isEmpty()) {
            return redirect()->back()->with('error', 'No addresses found for the authenticated user.');
        }
    
        return view('user.account-address', compact('address'));
    }
    

    public function orders(){
        $orders = Order::where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->paginate(12);
        return view('user.orders',compact('orders'));
    }

    public function order_details($order_id){
        $order = Order::where('user_id',Auth::user()->id)->where('id',$order_id)->first();
        if($order){
            $orderItems = OrderItem::where('order_id',$order->id)->orderBy('id')->paginate(12);
            $transaction = Transaction::where('order_id',$order->id)->first();
            return view('user.order-details',compact('order','orderItems', 'transaction'));
        } else {
            return redirect('')->route('login');
        }
    }

    public function cancel_order(Request $request){
        $order = Order::find($request->order_id);
        $order->status = "canceled";
        $order->canceled_date = Carbon::now();
        $order->save();
        return back()->with('status','Order is now cancelled');
    }
}
