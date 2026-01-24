<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\MasterUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;




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


// start route super admin
Route::middleware('role:super_admin')->group(function (){
    route::get('/admin/index', [DashboardController::class, 'index'])->name('admin.index');
    route::get("admin/masteruser/data",[MasterUserController::class,'data'])->name('admin.masteruser.data');
    route::resource('admin/masteruser',MasterUserController::class);
    route::get('/admin/guru/data',[GuruController::class,'data'])->name('admin.guru.data');
    route::resource('/admin/guru',GuruController::class);
});

// start route guru
Route::middleware('role:guru')->group(function (){
    route::get('/guru/index', [HomeController::class, 'guru'])->name('guru.index');
});

