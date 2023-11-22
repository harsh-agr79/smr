<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function login(){
        if(session()->has('ADMIN_LOGIN')){
            return redirect('/dashboard');
        }
        elseif(session()->has('USER_LOGIN')){
            return redirect('/user/home');
        }
        else{
            return view('login');
        }
    }
    public function superuser(){
        DB::table('admins')->insert([
            'name'=>"Harsh",
            'email'=>"agrharsh4321@gmail.com",
            'userid'=>"adminharsh",
            'password'=>Hash::make("7932yq4wy"),
            'type'=>"superuser"
        ]);
    }
    public function admins(){
        $result['data'] = Admin::whereNot('type', 'superuser')->get();
        return view('admin.admins',$result);
    }
    public function getadmin($id){
        $admin = Admin::where('id', $id)->first();
        return response()->json($admin, 200);
    }
    public function editadmin(Request $request){
        $request->validate([
            'userid'=>'required|unique:admins,userid|unique:customers,userid'.$request->post('id'),           
            'email'=>'required|unique:admins,email|unique:customers,email'.$request->post('id'),         
        ]);

        $name = $request->post('name');
        $email = $request->post('email');
        $userid = $request->post('userid');
        $password = $request->post('password');
        $id = $request->post('id');

        if($password == NULL){
            $password = Admin::where('id', $id)->first()->password;
        }
        else{
            $password = Hash::make($password);
        }
        DB::table('admins')->where('id', $id)->update([
            'name'=>$name,
            'email'=>$email,
            'userid'=>$userid,
            'password'=>$password,
        ]);
        return response()->json($request->post(), 200);
    }
    public function getadmindata(){
        $admin = Admin::whereNot('type', 'superuser')->get();
        return response()->json($admin, 200);
    }
    public function addadmin(Request $request){
        $request->validate([
            'userid'=>'required|unique:admins,userid,',           
        ]);
        $name = $request->post('name');
        $email = $request->post('email');
        $userid = $request->post('userid');
        $password = $request->post('password');
        $id = $request->post('id');

        DB::table('admins')->insert([
            'name'=>$name,
            'email'=>$email,
            'userid'=>$userid,
            'password'=>Hash::make($password),
            'type'=>"admin"
        ]);
        return response()->json($request->post('id'), 200);
    }
    public function deladmin($id){
        $admin = DB::table('admins')->where('id', $id)->first();
        if($admin->type == 'superuser'){
            return response()->json("Doesn't Exist.", 200);
        }
        else{
            Admin::where('id', $id)->delete();
            return response()->json("Admin Deleted!", 200);
        }
    }
    public function profile(){
        return view('admin/profile');
    }
    public function changepassword(Request $request){
        $old = $request->post('old');
        $new = $request->post('new');
        $new2 = $request->post('newagain');
        $admin = DB::table('admins')->where('id', session()->get('ADMIN_ID'))->first();

        if(Hash::check($old, $admin->password)){
            if($new == $new2){
                DB::table('admins')->where('id', session()->get('ADMIN_ID'))->update([
                    'password'=>Hash::make($new)
                ]);
                $msg = "Password Changed";
            }
            else{
                $msg = "New passwords Dont Match";
            }
        }
        else{
            $msg = "Current Password In correct";
        }
        return response()->json($msg);
    }
}
