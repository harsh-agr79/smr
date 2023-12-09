<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Image;

class ProductController extends Controller
{
    public function products(){
        $result['data'] = Product::get();
        return view('admin.products',$result);
    }
    public function addproduct(){
        $result['brands'] = DB::table("brands")->get();
        $result['category'] = DB::table("categories")->get();
        return view('admin/addproduct', $result);
    }
    public function addprod_process(Request $request){
        $request->validate([
            'name'=>'required|unique:products,name,'.$request->post('id'),                 
        ]);
        $image = array();
        if($files = $request->file('images')){
            $a = 0;
            $b = "";
            foreach($files as $file) {
                $a = $a + 1;
                $ext = $file->getClientOriginalExtension();
                $image_name = time().$a.'prod'.'.'.$ext;
                $image_resize = Image::make($file->getRealPath());
                // $image_resize->fit(300);
                $image_resize->save('product/'.$image_name);
                array_push($image, 'product/'.$image_name);
            }
        }
        
       DB::table('products')->insert([
        'name'=>$request->post('name'),
        'category_id'=>$request->post('category_id'),
        'category'=>DB::table('categories')->where('id', $request->post('category_id'))->first()->category,
        'brand_id'=>$request->post('brand_id'),
        'brand'=>DB::table('brands')->where('id', $request->post('brand_id'))->first()->name,
        'stock'=>$request->post('stock'),
        'hide'=>$request->post('hide'),
        'price'=>$request->post('price'),
        'featured'=>$request->post('featured'),
        'details'=>$request->post('details'),
        'images'=>implode("|",$image)
       ]);
       return redirect('/products');
    }
    public function editproduct($id){
        $prod = DB::table('products')->where('id', $id)->first();
        $result['brands'] = DB::table("brands")->whereNot('id', $prod->brand_id)->get();
        $result['category'] = DB::table("categories")->whereNot('id', $prod->category_id)->get();
        $result['prod'] = $prod;

        return view('admin/editproduct', $result);
    }
    public function editprod_process(Request $request){
        $request->validate([
            'name'=>'required|unique:products,name,'.$request->post('id'),                 
        ]);
        $image = array();
        if($files = $request->file('images')){
            $a = 0;
            $b = "";
            foreach($files as $file) {
                $a = $a + 1;
                $ext = $file->getClientOriginalExtension();
                $image_name = time().$a.'prod'.'.'.$ext;
                $image_resize = Image::make($file->getRealPath());
                $image_resize->fit(300);
                $image_resize->save('product/'.$image_name);
                array_push($image, 'product/'.$image_name);
            }
        }
        $oldimg = $request->post('oldimg', []);
        $prod = DB::table('products')->where('id',$request->post('id'))->first();
        $dbimgs = explode("|", $prod->images);
        foreach($dbimgs as $item){
            if(in_array($item, $oldimg)){
                array_push($image, $item);
            }
            else{
                if(File::exists($item)){
                    File::delete($item);
                }
            }
        }

        DB::table('products')->where('id', $request->post('id'))->update([
            'name'=>$request->post('name'),
            'category_id'=>$request->post('category_id'),
            'category'=>DB::table('categories')->where('id', $request->post('category_id'))->first()->category,
            'brand_id'=>$request->post('brand_id'),
            'brand'=>DB::table('brands')->where('id', $request->post('brand_id'))->first()->name,
            'stock'=>$request->post('stock'),
            'hide'=>$request->post('hide'),
            'price'=>$request->post('price'),
            'featured'=>$request->post('featured'),
            'details'=>$request->post('details'),
            'images'=>implode("|", $image)
           ]);
        
           return redirect('/products');
    }
    public function deleteproduct($id){
        $prod = Product::where('id', $id)->first();
        $imgs = explode("|", $prod->images);
        foreach($imgs as $item){
            if(File::exists($item)){
                File::delete($item);
            }
        }
        Product::where('id', $id)->delete();
        return redirect('/products');
}
}
