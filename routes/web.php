<?php

use App\Http\Controllers\Admin\AngketSoalController;
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
    route::post('admin/guru/import',[GuruController::class, 'import'])->name('admin.guru.import');
    route::resource('/admin/guru',GuruController::class);
    route::get('admin/angket/data',[AngketController::class,'data'])->name('admin.angket.data');
    route::resource('admin/angket',AngketController::class);
    route::get('admin/angketsoal/data',[AngketSoalController::class, 'data'])->name('admin.angketSoal.data');
    route::resource('admin/angketsoal',AngketSoalController::class);
});

// start route guru
Route::group(['prefix' => 'guru', 'middleware' => 'role:guru'], function (){
    Route::get('dashboard', [HomeController::class, 'guru'])->name('guru.index');
    Route::post('sekolah/data', [SekolahController::class, 'index'])->name('guru.sekolah.data');
    Route::resource('sekolah', SekolahController::class)
    ->names('guru.sekolah');

    Route::post('kelas/data', [KelasController::class, 'index'])->name('guru.kelas.data');
    Route::resource('kelas', KelasController::class)
    ->except('show')
    ->parameter('kelas', 'kelas')
    ->names('guru.kelas');

    Route::post('angket/data', [AngketController::class, 'index'])->name('guru.angket.data');
    Route::resource('angket', AngketController::class)
    ->names('guru.angket');



// Siswa
route::get('/siswa/form_angket',[FormAngket::class, 'index'])->name('siswa.formAngket');
});

