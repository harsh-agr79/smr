<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerViewController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AdminController::class, 'login']);
// Route::get('/adduser', [AdminController::class, 'superuser']);

Route::post('/auth', [LoginController::class, 'auth'])->name('auth');

Route::get('/logout', function(){
    session()->flush();
    session()->flash('error','Logged Out');
    return redirect('/');
});

Route::group(['middleware'=>'AdminAuth'], function(){
    Route::get('/dashboard', [LoginController::class, 'dashboard']);

    Route::get('/admin/profile', [AdminController::class, 'profile']);
    Route::post('/admin/changepassword', [AdminController::class, 'changepassword']);

    //Admins
    Route::get('/admins', [AdminController::class, 'admins']);
    //AJAX ADMIN
    Route::get('/admin/getdata/{id}', [AdminController::class, 'getadmin']);
    Route::get('/admin/getadmindata', [AdminController::class, 'getadmindata']);
    Route::post('/admin/editadmin', [AdminController::class, 'editadmin']);
    Route::post('/admin/addadmin', [AdminController::class, 'addadmin']);
    Route::get('/admin/deladmin/{id}', [AdminController::Class, 'deladmin']);

    //Customer
    Route::get('/customers', [CustomerController::class, 'customers']);
    Route::get('/customers/add', [CustomerController::class, 'addcustomer']);
    Route::post('/customers/addpro', [CustomerController::class, 'addcus_process'])->name('addcust');
    Route::get('/customers/edit/{id}', [CustomerController::class, 'editcustomer']);
    Route::post('customers/editcus', [CustomerController::class, 'editcus_process'])->name('editcust');
    Route::get('/customers/delcust/{id}', [CustomerController::class, 'deletecustomer']);

     //Product
     Route::get('/products', [ProductController::class, 'products']);
     Route::get('/products/add', [ProductController::class, 'addproduct']);
     Route::post('/products/addpro', [ProductController::class, 'addprod_process'])->name('addprod');
     Route::get('/products/edit/{id}', [ProductController::class, 'editproduct']);
     Route::post('products/editprod', [ProductController::class, 'editprod_process'])->name('editprod');
     Route::get('/products/delprod/{id}', [ProductController::class, 'deleteproduct']);

    //brands
    Route::get('/brands', [BrandController::class, 'index']);
    //AJAX BRANDS
    Route::get('/brand/getdata/{id}', [BrandController::class, 'getbrand']);
    Route::get('/brand/getbranddata', [BrandController::class, 'getbranddata']);
    Route::post('/brand/editbrand', [BrandController::class, 'editbrand']);
    Route::post('/brand/addbrand', [BrandController::class, 'addbrand']);
    Route::get('/brand/delbrand/{id}', [BrandController::Class, 'delbrand']);

    //category
    Route::get('/category', [CategoryController::class, 'index']);
    //AJAX BRANDS
    Route::get('/category/getdata/{id}', [CategoryController::class, 'getcategory']);
    Route::get('/category/getcatdata', [CategoryController::class, 'getcategorydata']);
    Route::post('/category/editcat', [CategoryController::class, 'editcategory']);
    Route::post('/category/addcat', [CategoryController::class, 'addcategory']);
    Route::get('/category/delcat/{id}', [CategoryController::Class, 'delcategory']);
});

Route::group(['middleware'=>'CustomerAuth'], function(){
    Route::get('/user/home', [HomeController::class, 'home']);

    Route::get('/user/profile', [CustomerViewController::class, 'profile']);
    Route::post('/user/updateprofile', [CustomerViewController::class, 'updateprofile'])->name('editprofile');
});


