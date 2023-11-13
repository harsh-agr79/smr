<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function auth(Request $request){
        $userid = $request->post('userid');
        $password = $request->post('password');

        $admin = DB::table('admins')->where(['userid'=>$userid])->first();
        $customer = DB::table('customers')->where('userid',$userid)->first();
        if($admin!=NULL){
            if (Hash::check($request->post('password'), $admin->password)) {
                $request->session()->put('ADMIN_LOGIN', true);
                $request->session()->put('ADMIN_ID', $admin->id);
                $request->session()->put('ADMIN_TIME', time() );
                $request->session()->put('ADMIN_TYPE', $admin->type);
    
                return redirect('/');
            }
            else{
                $request->session()->flash('error','please enter valid login details');
                return redirect('/');
            }
        }
        elseif($customer!=NULL){
            if (Hash::check($request->post('password'), $customer->password)) {
                $request->session()->put('USER_LOGIN', true);
                $request->session()->put('USER_ID', $customer->id);
                $request->session()->put('USER_TIME', time() );
    
                return redirect('/');
                }
                else{
                    $request->session()->flash('error','please enter valid login details');
                    return redirect('/');
                }
        }
        else{
            $request->session()->flash('error','please enter valid login details');
            return redirect('/');
        }
    }
    public function dashboard(){
        return view('admin.dashboard');
    }
}
