<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;

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

    Route::get('/admins', [AdminController::class, 'admins']);

    //AJAX ADMIN
    Route::get('/admin/getdata/{id}', [AdminController::class, 'getadmin']);
    Route::get('/admin/getadmindata', [AdminController::class, 'getadmindata']);
    Route::post('/admin/editadmin', [AdminController::class, 'editadmin']);
    Route::post('/admin/addadmin', [AdminController::class, 'addadmin']);
    Route::get('/admin/deladmin/{id}', [AdminController::Class, 'deladmin']);
});
