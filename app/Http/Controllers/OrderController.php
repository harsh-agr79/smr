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
}

