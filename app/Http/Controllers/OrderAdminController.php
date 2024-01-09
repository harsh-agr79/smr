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
}
