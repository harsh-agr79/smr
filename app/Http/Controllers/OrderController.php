<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function confirmcart(){
        $user = DB::table('customers')->where("id", session()->get('USER_ID'))->first();
        $cart = $user->cart;
        $break = explode(":", $cart);
        $products = explode(",", $break[0]);
        $qty = explode(",", $break[1]);
        for ($i=0; $i < count($products); $i++) {
            if($qty[$i] > 0){
                $prod = DB::table('products')->where("id", $products[$i])->first();
                DB::table("orders")->insert([
                    'date'=>date('Y-m-d H:i:s'),
                    'order_id'=>$user->id.getNepaliDay(date('Y-m-d H:i:s')).getNepaliMonth(date('Y-m-d H:i:s')).getNepaliYear(date('Y-m-d H:i:s')).date("His"),
                    'name'=>$user->name,
                    'user_id'=>$user->id,
                    'item'=>$prod->name,
                    'product_id'=>$prod->id,
                    'brand'=>$prod->brand,
                    'brand_id'=>$prod->brand_id,
                    'category'=>$prod->category,
                    'category_id'=>$prod->category_id,
                    'price'=>$prod->price,
                    'quantity'=>$qty[$i],
                    'approvedquantity'=>"0",
                    'mainstatus'=>"blue",
                    'status'=>"pending",
                    'discount'=>"0",
                    'nepday'=>getNepaliDay(date('Y-m-d H:i:s')),
                    'nepmonth'=>getNepaliMonth(date('Y-m-d H:i:s')),
                    'nepyear'=>getNepaliYear(date('Y-m-d H:i:s'))
                ]);
            }   
        }
        DB::table('customers')->where("id", session()->get('USER_ID'))->update([
            'cart'=>NULL
        ]);
        return redirect("/user/home");
    }
    public function savecart(){
        $user = DB::table('customers')->where("id", session()->get('USER_ID'))->first();
        $cart = $user->cart;
        $break = explode(":", $cart);
        $products = explode(",", $break[0]);
        $qty = explode(",", $break[1]);
        for ($i=0; $i < count($products); $i++) {
            if($qty[$i] > 0){
                $prod = DB::table('products')->where("id", $products[$i])->first();
                DB::table("orders")->insert([
                    'date'=>date('Y-m-d H:i:s'),
                    'order_id'=>$user->id.getNepaliDay(date('Y-m-d H:i:s')).getNepaliMonth(date('Y-m-d H:i:s')).getNepaliYear(date('Y-m-d H:i:s')).date("His"),
                    'name'=>$user->name,
                    'user_id'=>$user->id,
                    'item'=>$prod->name,
                    'product_id'=>$prod->id,
                    'brand'=>$prod->brand,
                    'brand_id'=>$prod->brand_id,
                    'category'=>$prod->category,
                    'category_id'=>$prod->category_id,
                    'price'=>$prod->price,
                    'quantity'=>$qty[$i],
                    'approvedquantity'=>"0",
                    'mainstatus'=>"blue",
                    'status'=>"pending",
                    'discount'=>"0",
                    'nepday'=>getNepaliDay(date('Y-m-d H:i:s')),
                    'nepmonth'=>getNepaliMonth(date('Y-m-d H:i:s')),
                    'nepyear'=>getNepaliYear(date('Y-m-d H:i:s')),
                    'save'=>'save'
                ]);
            }   
        }
        DB::table('customers')->where("id", session()->get('USER_ID'))->update([
            'cart'=>NULL
        ]);
        return redirect("/user/home");
    }

    public function oldorders(Request $request){
        $cust = DB::table('customers')->where('id', session()->get('USER_ID'))->first();
        $name = $cust->name;
        $query = DB::table('orders');
        $query = $query->where(['deleted_at'=>NULL, 'save'=>NULL, 'name'=>$name])->orderBy('date', 'DESC')->groupBy('order_id');
        if($request->get('date')){
           $query = $query->whereDate('date', $request->get('date'));
           $result['date']= $request->get('date');
        }
        else{
            $result['date']= '';
        }
        $query = $query->paginate(10);
        $result['data']=$query;
        $result['page'] = 'All Orders';
    

        return view('customer/orders', $result);
    }
    public function savedorders(Request $request){
        $cust = DB::table('customers')->where('id', session()->get('USER_ID'))->first();
        $name = $cust->name;
        $query = DB::table('orders');
        $query = $query->where(['deleted_at'=>NULL, 'save'=>'save', 'name'=>$name])->orderBy('date', 'DESC')->groupBy('order_id');
        if($request->get('date')){
           $query = $query->whereDate('date', $request->get('date'));
           $result['date']= $request->get('date');
        }
        else{
            $result['date']= '';
        }
        $query = $query->paginate(10);
        $result['data']=$query;
        $result['page'] = 'Saved Baskets';
    

        return view('customer/orders', $result);
    }
    public function detail(Request $request, $orderid){
        $result['data'] = DB::table('orders')->where('order_id', $orderid) 
        ->join('products', 'orders.product_id', '=', 'products.id')
        ->selectRaw('orders.*, products.stock')
        ->get();

        return view('customer/detail', $result);
    }
    public function detailedit(Request $request){
        $orderid = $request->post('order_id');
        DB::table('orders')->where('order_id', $orderid)->update([
            'userremarks'=>$request->post('userremarks')
        ]);
        return redirect('user/oldorders');
    }
    public function recieveorder(Request $request, $id){
        $order = DB::table('orders')->where('order_id', $id)->get();
        if($order[0]->received == NULL){
            $val = 'on';
            $date = date('Y-m-d H:i:s');
        }
        else{
            $val = NULL;
            $date = NULL;
        }
        DB::table('orders')->where('order_id', $id)->update([
            'received'=>$val,
            'receiveddate'=>$date
        ]);
        $msg = 'success';
        return response()->json($msg,200);
    }
    public function confirm(Request $request, $orderid)
    {
        DB::table('orders')->where('order_id', $orderid)->update([
            'date'=>date('Y-m-d H:i:s'),
            'save'=>NULL
        ]);
        return redirect('user/oldorders');
    }
}

