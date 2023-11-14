<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Image;

class CustomerViewController extends Controller
{
    public function profile(){
        return view('customer/profile');
    }

    public function updateprofile(Request $request){
        if($file = $request->file('dp')){

            if(File::exists($request->post('olddp'))) {
                File::delete($request->post('olddp'));
            }
            $file = $request->file('dp');
            $ext = $file->getClientOriginalExtension();
            $image_name = $request->post('id').time().'userdp'.'.'.$ext;
            $image_resize = Image::make($file->getRealPath());
            $image_resize->fit(300);
            $image_resize->save('customerdp/'.$image_name);
            $image = 'customerdp/'.$image_name;
                DB::table('customers')->where('id', $request->post('id'))->update([
                    'profileimg'=>$image
                ]);
        }
        DB::table('customers')->where('id', $request->post('id'))->where('uniqueid', $request->post('uniqueid'))->update([
            'dob'=>$request->post('dob'),
            'contact'=>$request->post('contact'),
            'contact2'=>$request->post('contact2'),
            'address'=>$request->post('address'),
            'tax_type'=>$request->post('tax_type'),
            'tax_number'=>$request->post('tax_number'),
           ]);
        
           return redirect('/user/profile');
    }
}
