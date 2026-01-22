<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataPeringkatController;
use App\Http\Controllers\Admin\MasterUserController;
use App\Http\Controllers\Admin\PeriodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PemeringkatanController;
use App\http\Controllers\Admin\IndikatorPeringkatController;;


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
    route::get("admin/role/data",[RoleController::class, 'data'])->name('admin.role.data');
    route::resource("admin/role",RoleController::class);
    route::get("/admin/masterperingkat/data",[PemeringkatanController::class,'data'])->name('admin.masterperingkat.data');
    route::resource('admin/masterperingkat',PemeringkatanController::class);
    route::get("/admin/indikator/data/{indikator}",[IndikatorPeringkatController::class,'data'])->name('admin.indikator.data');
    route::resource('admin/indikator', IndikatorPeringkatController::class);
    route::get('admin/periode/data', [PeriodeController::class, 'data'])->name('admin.periode.data');
    route::resource('admin/periode', PeriodeController::class);
    route::get("admin/pemeringkatan/data/{id}",[DataPeringkatController::class,'data'])->name('admin.dataPeringkat.data');    
    route::resource('admin/pemeringkatan',DataPeringkatController::class);

});

