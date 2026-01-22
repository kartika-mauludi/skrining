<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MasterUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;



use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function(){
    return view('Auth.login');
});
Route::get('/register', function(){
    return view('Auth.register');
});
Route::POST('/login', [LoginController::class, 'login'])->name('login');
Route::POST('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class,'logout'])->name('logout');



Route::middleware('role:super_admin')->group(function (){
    route::get('/admin/index', [DashboardController::class, 'index'])->name('admin.index');
    route::get("admin/masteruser/data",[MasterUserController::class,'data'])->name('admin.masteruser.data');
    route::resource('admin/masteruser',MasterUserController::class);
});

