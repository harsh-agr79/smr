<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function updatecart(Request $request){
        $prod = $request->post('prod',[]);
        $qt = $request->post('qt', []);
        $cart = $prod.":".$qt;
        DB::table('customers')->where("id", session()->get('USER_ID'))->update([
            'cart'=>$cart
        ]);
        return response()->json($prod);
    }

    public function getcart(){
        $cart = DB::table('customers')->where("id", session()->get('USER_ID'))->first()->cart;
        $break = explode(":", $cart);
        $products = explode(",", $break[0]);
        $qty = explode(",", $break[1]);
        $data = array();
        for ($i=0; $i < count($products); $i++) {
            if($qty[$i] > 0){
                $prod = DB::table('products')->where("id", $products[$i])->first();
                $data[] = [
                    'name'=>$prod->name,
                    'image'=>explode("|", $prod->images)[0],
                    'price'=>$prod->price,
                    'quantity'=>$qty[$i],
                    'total'=>$prod->price * $qty[$i],
                    'brand'=>$prod->brand,
                    'category'=>$prod->category,
                ];
            }   
        }
        return response()->json($data);
    }
}
