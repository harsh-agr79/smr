<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderAdminController extends Controller
{
    public function orders(Request $request){
        $result['date']= '';
        $result['date2']= '';
        $result['status']= '';
        $result['product']= '';
        $result['name']= '';

        $query = DB::table('orders');
        $query = $query->where(['deleted_at'=>NULL, 'save'=>NULL])->orderBy('date', 'DESC');
        if($request->get('name')){
           $query = $query->where('name', $request->get('name'))->groupBy('order_id');
           $result['name']= $request->get('name');
        }
        else{
            $result['name'] ='';
        }
        if($request->get('date')){
           $query = $query->where('date', '>=', $request->get('date'))->groupBy('order_id');
           $result['date']= $request->get('date');
        }
        if($request->get('date2')){
            $query = $query->where('date', '<=', $request->get('date2'))->groupBy('order_id');
            $result['date2']= $request->get('date2');
         }
         if($request->get('status') && $request->get('product') == ''){
            $query = $query->where('status',$request->get('status'))->groupBy('order_id');
            $result['status']= $request->get('status');
         }
         if($request->get('status') == '' && $request->get('product') != ''){
            $query = $query->where('item',$request->get('product'));
            $result['product'] = $request->get('product');
         }
         if($request->get('status') && $request->get('product') != ''){
            $query = $query->where('status',$request->get('status'));
            $query = $query->where('item',$request->get('product'));
            $result['status']= $request->get('status');
            $result['product'] = $request->get('product');
         }
         else{
            $query = $query->groupBy('order_id');
         }
        $query = $query->paginate(50);
        $result['data']=$query;
    

        return view('admin/orders', $result);
    }

    public function approvedorders(Request $request){
        $result['data'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'save'=>NULL])
        ->whereIn('mainstatus', ['amber darken-1', 'deep-purple'])
        ->groupBy('order_id')
        ->orderBy('date', 'DESC')
        ->get();

        return view('admin/approvedorders', $result);
    }
    public function pendingorders(Request $request){
        $result['data'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'save'=>NULL])
        ->where('mainstatus', 'blue')
        ->groupBy('order_id')
        ->orderBy('date', 'DESC')
        ->get();

        return view('admin/pendingorders', $result);
    }
    public function rejectedorders(Request $request){
        $result['data'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'save'=>NULL])
        ->where('mainstatus', 'red')
        ->groupBy('order_id')
        ->orderBy('date', 'DESC')
        ->get();

        return view('admin/rejectedorders', $result);
    }
    public function deliveredorders(Request $request){
        $result['data'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'save'=>NULL])
        ->where('mainstatus', 'green')
        ->groupBy('order_id')
        ->orderBy('date', 'DESC')
        ->paginate(50);

        return view('admin/deliveredorders', $result);
    }

    public function details(Request $request, $orderid){
        $result['data'] = DB::table('orders')->where('order_id', $orderid) 
        ->join('products', 'orders.product_id', '=', 'products.id')
        ->selectRaw('orders.*, products.stock')
        ->get();

        return view('admin/orderdetail', $result);
    }

    public function detailupdate(Request $request){
        $id = $request->post('id',[]);
        $apquantity = $request->post('apquantity', []);
        $quantity = $request->post('quantity', []);
        $price = $request->post('price', []);
        $status = $request->post('status', []);
        $discount = $request->post('discount');
        for ($i=0; $i < count($id); $i++) { 
            if($status[$i] == 'approved'){
                if($apquantity[$i] > 0){
                    $qty = $apquantity[$i];
                }
                else{
                    $qty = $quantity[$i];
                }
            }
            else{
                $qty = 0;
            }
            DB::table('orders')->where('id', $id[$i])->update([
                'approvedquantity'=>$qty,
                'price'=>$price[$i],
                'status'=>$status[$i],
                'remarks'=>$request->post('remarks'),
                'cartoons'=>$request->post('cartoons'),
                'transport'=>$request->post('transport'),
                'discount'=>$discount,
            ]);
        }
        updateMainStatus($request->post('order_id'));

        if($request->post('previous') == url('editorder/'.DB::table('orders')->where('id', $id[0])->first()->order_id)){
            return redirect('dashboard');
        }
        else{
            return redirect($request->post('previous'));
        }
    }

    public function seenupdate(Request $request){
        $orderid = $request->post('order_id');
        $admin = $request->post('admin');

        DB::table('orders')->where('order_id',$orderid)->update([
            'seen'=>'seen',
            'seenby'=>$admin,
        ]);

        return response()->json('200');
    }
    public function updatedeliver(Request $request){
        $orderid = $request->post('order_id');
        $delivered = $request->post('delivered');
        if($delivered == 'on'){
            $packorder = 'delivered';
        }
        else{
            $packorder = 'packorder';
        }
        DB::table('orders')->where('order_id',$orderid)->update([
            'delivered'=>$delivered,
            'clnstatus'=>$packorder
        ]);
        updateMainStatus($orderid);
        return response()->json($request->all());
    }

    public function save($orderid){
        $result['data'] = DB::table('orders')->where('order_id',$orderid)->where('status', 'approved')->get();
        return view('admin/saveorder', $result);
    }
    public function print($orderid){
        $result['data'] = DB::table('orders')->where('order_id',$orderid)->where('status', 'approved')->get();
        return view('admin/printorder', $result);
    }
    public function bprintindex(Request $request){
        $query = DB::table('orders')->where('deleted_at', NULL)->where('save', NULL)->orderBy('date', 'DESC')->groupBy('order_id');
        $result['date'] = '';
        $result['date2'] =  '';
        $result['name'] =  '';
        if($request->get('date')){
            $query = $query->where('date', '>=', $request->get('date'));
            $result['date'] =  $request->get('date');
        }
        if($request->get('date2')){
            $query = $query->where('date', '<=', $request->get('date2'));
            $result['date2'] =  $request->get('date2');
        }
        if($request->get('name')){
            $query = $query->where('name', $request->get('name'));
            $result['name'] =  $request->get('name');
        }
        $query = $query->paginate(45);
        $result['data'] = $query;

        return view('admin/bprintindex', $result);
    }

    public function bulkprint(Request $request){
        $result['orderids'] = $request->post('order_id',[]);

        return view('admin/bulkprint', $result);
    }

    public function addorder(Request $request){
        $result['brands'] = DB::table('brands')->get();
        $result['category'] = DB::table('categories')->get();
        $result['data'] = DB::table('products')->orderBy('brand', 'DESC')->get();
        return view("admin.addorder", $result);
    }
    public function createorder(Request $request){
        $user = DB::table('customers')->where("name",$request->post("name"))->first();
        $products = $request->post("prodid",[]);
        $qty = $request->post("quantity", []);
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
        return redirect("/pendingorders");
    }
}
