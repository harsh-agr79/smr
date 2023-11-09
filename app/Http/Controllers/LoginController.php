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
        else{
            $request->session()->flash('error','please enter valid login details');
            return redirect('/');
        }
    }
    public function dashboard(){
        return view('admin.dashboard');
    }
}
