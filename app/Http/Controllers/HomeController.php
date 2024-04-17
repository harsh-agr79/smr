<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home(){
        $user = DB::table('customers')->where('id', session()->get("USER_ID"))->first();
        $result['prods'] = DB::table('products')->whereIn('brand_id', explode('|', $user->brands))->where("hide", NULL)->orderBy("ordernum", 'ASC')->get();
        $result['brands'] = DB::table('brands')->whereIn('id', explode('|', $user->brands))->get();
        $result['category'] = DB::table('categories')->get();
        return view('customer/home', $result);
    }
    public function homereal(){
        $result['data']=DB::table('front')->where('type', 'image')->get();
        $result['data2']=DB::table('front')->where('type', 'message')->get();
        return view('customer/homereal', $result);
    }
}
