<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function statement(Request $request){
        $result['data'] = DB::table('customers')->orderBy('name', 'ASC')->get();

        return view('admin/statement', $result);
    }
    public function balancesheet(Request $request, $id){

        $cust = DB::table('customers')->where('id', $id)->first();
        $result['cus'] = $cust;
        $today = date('Y-m-d');
        //  $target = DB::table('target')->where('userid',$cust->user_id)
        //  ->where('startdate', '<=', $today)
        //  ->where('enddate', '>=', $today)
        //  ->get();

        //  if(count($target) > 0){
        //     $rdate = $target['0']->startdate;
        //     $rdate2 = $target['0']->enddate;

        //     if($request->get('date') && $request->get('date2'))
        //         {
        //             $date = $request->get('date');
        //             $date2 = $request->get('date2');
        //         }
        //         elseif($request->get('date')){
        //             $date = $request->get('date');
        //             $date2 = $rdate2;
        //         }
        //         elseif($request->get('date2')){
        //             $date2 = $request->get('date2');
        //             $date = $rdate2;
        //         }
        //         elseif($request->get('clear')){
        //             $date = $rdate;
        //             $date2 = $rdate2;
        //          }
        //         else{
        //             $date = $rdate;
        //             $date2 = $rdate2;
        //         }

        //  }
        //  else{
            if($request->get('date') && $request->get('date2'))
            {
                $date = $request->get('date');
                $date2 = $request->get('date2');
            }
            elseif($request->get('date')){
                $date = $request->get('date');
                $date3 = date('Y-10-18');
                $date2 = date('Y-m-d', strtotime($date3. ' + 1 year -1 day'));
            }
            elseif($request->get('date2')){
                $date2 = $request->get('date2');
                $date = date('Y-10-18');
            }
            elseif($request->get('clear')){
               if(date('Y-m-d') < date('Y-10-18') ){
                $date2 = date('Y-10-17');  
                $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
               }
               else{
                   $date = date('Y-10-18');
                   $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
               }
            }
            else{
                if(date('Y-m-d') < date('Y-10-18') ){
                $date2 = date('Y-10-17');  
                $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
               }
               else{
                   $date = date('Y-10-18');
                   $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
               }
            }
        //  }
        $result['date'] = $date;
        $result['date2'] = $date2;
        $date2 = date('Y-m-d', strtotime($date2. ' +1 day'));

        $result['oldorders'] = DB::table('orders')
        ->where('date', '<', $date)
        ->where('user_id',$id)
        ->where('net', NULL)
        ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('name')->where('status','approved') 
        ->get();

        $result['oldpayments'] = DB::table('payments')
        ->where(['deleted'=>NULL])
        ->where('date', '<', $date)
        ->where('user_id',$id)
        ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
        ->get();

        $result['oldslr'] = DB::table('salesreturns')
           ->where('user_id', $id)
           ->where('date', '<', $date)
           ->selectRaw('*, SUM(quantity * price) as sum, SUM(discount*0.01 * quantity * price) as dis')->groupBy('name') 
           ->get();
           
       $result['oldexp'] = DB::table('expenses')
           ->where('user_id', $id)
           ->where('date', '<', $date)
           ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
           ->get();

           $result['cuorsum'] = DB::table('orders')
           ->where(['save'=>NULL])
           ->where('user_id', $id)
            ->where('net', NULL)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as sum')->groupBy('name')->where('status','approved') 
           ->get();

           $result['cupysum'] = DB::table('payments')
           ->where('deleted',NULL)
           ->where('user_id', $id)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
           ->get();

           $result['cuslrsum'] = DB::table('salesreturns')
           ->where('user_id', $id)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(quantity * price) as sum, SUM(discount*0.01 * quantity * price) as dis')->groupBy('name') 
           ->get();
           
            $result['cuexsum'] = DB::table('expenses')
           ->where('user_id', $id)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
           ->get();

        $orders = DB::table('orders')
        ->where(['save'=>null])
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->where('status','approved')
        ->where('user_id',$id)
        ->where('net', NULL)
        ->selectRaw('*, SUM(approvedquantity * price) as sum')->groupBy('order_id') 
        ->orderBy('orders.date','desc')
        ->get();

        $payments = DB::table('payments')->where('user_id', $id)
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->where('deleted',NULL)->get();

        $slrs = DB::table('salesreturns')
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->selectRaw('*, SUM(quantity * price) as sum, SUM(discount * 0.01 * quantity * price) as dis')->groupBy('returnid')->where('user_id',$id) 
        ->orderBy('date','desc')
        ->get();
        
        $exp = DB::table('expenses')
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->selectRaw('*, SUM(amount) as sum')->where('user_id', $id)
        ->orderBy('date', 'desc')
        ->get();

        $data = array();
        foreach($orders as $item){
            if($item->name == NULL){

            }
            else{
            $data[] = [
                'name'=>$item->name,
                'created'=>$item->date,
                'ent_id'=>$item->order_id,
                'debit'=>($item->sum * (1-$item->discount*0.01))*(1-0.01*$item->sdis),
                'nar'=>$item->remarks,
                'vou'=>'',
                'credit'=>'0',
                'type'=>'sale',
            ];}
        }
        foreach($payments as $item){
            if($item->name == NULL){

            }
            else{
            $data[] = [
                'name'=>$item->name,
                'created'=>$item->date,
                'ent_id'=>$item->paymentid,
                'id'=>$item->id,
                'debit'=>'0',
                'nar'=>'',
                'vou'=>$item->voucher,
                'credit'=>$item->amount,
                'type'=>$item->type,
            ];}
        }
        foreach($slrs as $item){
            if($item->name == NULL){

            }
            else{
            $data[] = [
                'name'=>$item->name,
                'created'=>$item->date,
                'ent_id'=>$item->returnid,
                'debit'=>'0',
                'nar'=>'',
                'vou'=>'',
                'credit'=>$item->sum - $item->dis,
                'type'=>'Sales Return',
            ];}
        }
        foreach($exp as $item){
            if($item->name == NULL){

            }
            else{
                $data[] = [
                    'name'=>$item->name,
                    'created'=>$item->date,
                    'ent_id'=>$item->expenseid,
                    'id'=>$item->id,
                    'debit'=>$item->amount,
                    'nar'=>'',
                    'vou'=>$item->particular,
                    'credit'=>'0',
                    'type'=>'expense',
                ];
            }   
        }
            usort($data, function($a, $b) {
                return strtotime($a['created']) - strtotime($b['created']);
            });

        $result['data'] = collect($data);
        return view('admin/balancesheet', $result);
    }
    public function mainanalytics(Request $request){
        if($request->get('date') && $request->get('date2'))
        {
            $date = $request->get('date');
            $date2 = $request->get('date2');
        }
        elseif($request->get('date')){
            $date = $request->get('date');
            $date3 = date('Y-10-18');
            $date2 = date('Y-m-d', strtotime($date3. ' + 1 year -1 day'));
        }
        elseif($request->get('date2')){
            $date2 = $request->get('date2');
            $date = date('Y-10-18');
        }
        elseif($request->get('clear')){
             if(date('Y-m-d') < date('Y-10-18') ){
             $date2 = date('Y-10-17');  
             $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
            }
            else{
                $date = date('Y-10-18');
                $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
            }
        }
        else{
            if(date('Y-m-d') < date('Y-10-18') ){
             $date2 = date('Y-10-17');  
             $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
            }
            else{
                $date = date('Y-10-18');
                $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
            }
            
        }
        $result['date'] = $date;
        $result['date2'] = $date2;
        $date2 = date('Y-m-d', strtotime($date2. ' +1 day'));

        $result['totalsales'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
        ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-1'])
        ->where('orders.date', '>=', $date)
        ->where('orders.date', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->selectRaw('*, SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')
        ->groupBy('deleted_at')
        ->get();
        
        $result['catsales'] = DB::table('orders')
        ->where(['deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
        ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-1'])
        ->where('orders.date', '>=', $date)
        ->where('orders.date', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')
        ->groupBy('brand')
        ->orderBy('samt','DESC')
        ->get();

        foreach($result['catsales'] as $item){
            $result['data'][$item->brand] = DB::table('products')
            ->where(['orders.brand'=>$item->brand,'status'=>'approved','orders.deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
            ->where('orders.date', '>=', $date)
            ->where('orders.date', '<=', $date2)
            ->where(function ($query) use ($request){
                if($request->get('name')){
                    $query->where('orders.name', $request->get('name'));
                }
            })
            ->join('orders', 'products.id', '=', 'orders.product_id')
            ->selectRaw('*, SUM(approvedquantity) as sum,SUM(approvedquantity * orders.price * (1-discount * 0.01) * (1-0.01*sdis)) as samt')->groupBy('orders.product_id')->orderBy('sum','desc')
            ->get();
            $result['data2'][$item->brand] = DB::table('products')
            ->where(['brand'=>$item->brand])
            ->whereNotIn('id', DB::table('orders')
            ->where(['brand'=>$item->brand,'status'=>'approved','deleted_at'=>NULL, 'save'=>NULL, 'orders.net'=>NULL])
            ->where('date', '>=', $date)
            ->where('date', '<=', $date2)
            ->where(function ($query) use ($request){
                if($request->get('name')){
                    $query->where('orders.name', $request->get('name'));
                }
            })
            ->pluck('product_id')
            ->toArray())
            ->get();
        }

        if($request->get('name')){
            $result['name'] = $request->get('name');
        }
        else{
            $result['name'] = "";
        }



        return view('admin/mainanalytics', $result);
    }
}
