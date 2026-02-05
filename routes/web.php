<?php

use App\Http\Controllers\Admin\AngketSoalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\MasterUserController;
use App\Http\Controllers\Admin\AngketController;
use App\Http\Controllers\Admin\SekolahController as AdminSekolahController;
use App\Http\Controllers\Admin\TanggapanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AngketController as AngketGuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\Siswa\FormAngketController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\SiswaController;


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
    Route::delete('/angket-soal/destroy-all', [AngketSoalController::class, 'destroyAll'])
    ->name('angketsoal.destroyAll');
    route::resource('admin/angketsoal',AngketSoalController::class);
    Route::get('admin/sekolah/data', [AdminSekolahController::class, 'data'])->name('admin.sekolah.data');
    Route::resource('admin/sekolah', AdminSekolahController::class)
    ->names('admin.sekolah');
    Route::get('admin/tanggapan/data',[TanggapanController::class,'data'])->name('admin.tanggapan.data');
     Route::delete('admin/tanggapan/destroy-all', [TanggapanController::class, 'destroyAll'])
    ->name('admin.tanggapan.destroyAll');
    route::resource('admin/tanggapan',TanggapanController::class);
});

// start route guru
Route::group(['prefix' => 'guru', 'middleware' => 'role:guru'], function (){
    Route::get('dashboard', [HomeController::class, 'guru'])->name('guru.dashboard');
    Route::post('sekolah/data', [SekolahController::class, 'index'])->name('guru.sekolah.data');
    Route::resource('sekolah', SekolahController::class)
    ->names('guru.sekolah');

    Route::post('kelas/{kelas}/token', [KelasController::class, 'token'])->name('guru.kelas.token');
    Route::post('kelas/data', [KelasController::class, 'index'])->name('guru.kelas.data');
    Route::resource('kelas', KelasController::class)
    ->except('show')
    ->parameter('kelas', 'kelas')
    ->names('guru.kelas');

    Route::post('angket/data', [AngketGuruController::class, 'index'])->name('guru.angket.data');
    Route::resource('angket', AngketGuruController::class)
    ->names('guru.angket');

    Route::post('siswa/import', [SiswaController::class, 'import'])->name('guru.siswa.import');
    Route::post('siswa/data', [SiswaController::class, 'index'])->name('guru.siswa.data');
    Route::resource('siswa', SiswaController::class)
    ->names('guru.siswa');
});

// Siswa
route::post('siswa/formAngket',[FormAngketController::class, 'store'])->name('siswa.formAngket.store');
route::get('siswa/formAngket',[FormAngketController::class, 'index'])->name('siswa.formAngket');
route::get('siswa/hasilAngket',[FormAngketController::class,'hasil'])->name('siswa.hasilAngket');

