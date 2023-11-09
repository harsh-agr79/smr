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
}
