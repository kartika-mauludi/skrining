<?php

use App\Http\Controllers\Admin\AngketSoalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\MasterUserController;
use App\Http\Controllers\Admin\AngketController;
use App\Http\Controllers\Admin\SekolahController as AdminSekolahController;
use App\Http\Controllers\Admin\TanggapanController;
use App\Http\Controllers\Admin\LogLoginController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Guru\AngketController as AngketGuruController;
use App\Http\Controllers\Guru\AngketSoalController as GuruAngketSoalController;
use App\Http\Controllers\Guru\FeedbackController;
use App\Http\Controllers\Guru\KelasController;
use App\Http\Controllers\Guru\ReportController;
use App\Http\Controllers\Siswa\FormAngketController;
use App\Http\Controllers\Guru\SekolahController;
use App\Http\Controllers\Guru\SiswaController;
use App\Http\Controllers\Guru\GuruController as ProfilGuruController;



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
Route::POST('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class,'logout'])->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('forgot-password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot-password');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password-reset-update');

// start route super admin
Route::middleware('role:super_admin')->group(function (){
    route::get('/admin/index', [DashboardController::class, 'index'])->name('admin.index');
    route::get('admin/pengaturan',[DashboardController::class,'profil'])->name('admin.profil');
    route::POST('admin/pemgaturan',[DashboardController::class,'pengaturan'])->name('admin.pengaturan');
    route::get("admin/masteruser/data",[MasterUserController::class,'data'])->name('admin.masteruser.data');
    route::resource('admin/masteruser',MasterUserController::class);
    
    route::get('/admin/guru/data',[GuruController::class,'data'])->name('admin.guru.data');
    route::post('admin/guru/import',[GuruController::class, 'import'])->name('admin.guru.import');
    route::resource('/admin/guru',GuruController::class);
    
    route::get('admin/angket/data',[AngketController::class,'data'])->name('admin.angket.data');
    route::resource('admin/angket',AngketController::class);
    
    route::get('admin/angketsoal/data',[AngketSoalController::class, 'data'])->name('admin.angketSoal.data');
    Route::post('admin/angketsoal/import',[AngketSoalController::class,'import'])->name('admin.angketSoal.import');
    Route::delete('/angket-soal/destroy-all', [AngketSoalController::class, 'destroyAll'])
    ->name('angketsoal.destroyAll');
    route::resource('admin/angketsoal',AngketSoalController::class);
    
    Route::get('admin/sekolah/data', [AdminSekolahController::class, 'data'])->name('admin.sekolah.data');
    Route::resource('admin/sekolah', AdminSekolahController::class)
    ->names('admin.sekolah');
   
    Route::get('admin/tanggapan/data',[TanggapanController::class,'data'])->name('admin.tanggapan.data');
    Route::post('admin/tanggapan/import',[TanggapanController::class,'import'])->name('admin.tanggapan.import');
     Route::delete('admin/tanggapan/destroy-all', [TanggapanController::class, 'destroyAll'])
    ->name('admin.tanggapan.destroyAll');
    route::resource('admin/tanggapan',TanggapanController::class);

    Route::get('/admin/log-login', [LogLoginController::class, 'index'])
    ->name('admin.log-login');

     Route::get('/admin/log-login', [LogLoginController::class, 'index'])
    ->name('admin.log-login');

    Route::get('admin/report',[AdminReportController::class,'index'])->name('admin.report');
    Route::get('/admin/sekolah/{sekolah}',[AdminReportController::class,'sekolah'])->name('admin.report.sekolah');
    Route::get('/report/export-csv', [AdminReportController::class, 'exportCsv'])
    ->name('report.export.csv');

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
    Route::get('angket', [AngketGuruController::class, 'index'])
    ->name('guru.angket.index');

    Route::post('angket-soal/data', [GuruAngketSoalController::class, 'index'])->name('guru.soal.data');
    Route::get('angket-soal/edit-soal', [GuruAngketSoalController::class, 'edit'])->name('guru.soal.edit');
    Route::resource('angket-soal', GuruAngketSoalController::class)
    ->except('show', 'edit')
    ->parameter('angket-soal', 'angketSoal')
    ->names('guru.soal');

    Route::post('tanggapan/import', [FeedbackController::class, 'import'])->name('guru.tanggapan.import');
    Route::post('tanggapan/data', [FeedbackController::class, 'index'])->name('guru.tanggapan.data');
    Route::resource('tanggapan', FeedbackController::class)
    ->except('show')
    ->parameter('tanggapan', 'feedback')
    ->names('guru.tanggapan');

    Route::post('siswa/import', [SiswaController::class, 'import'])->name('guru.siswa.import');
    Route::post('siswa/data', [SiswaController::class, 'index'])->name('guru.siswa.data');
    Route::resource('siswa', SiswaController::class)
    ->names('guru.siswa');

    Route::get('report', [ReportController::class, 'index'])->name('guru.report');
    Route::get('report-sosiogram', [ReportController::class, 'sosiogram'])->name('guru.report.sosiogram');
    Route::get('report-matriks', [ReportController::class, 'matriks'])->name('guru.report.matriks');
    Route::get('report-korban/{siswa}', [ReportController::class, 'korban'])->name('guru.report.korban');
    Route::post('report-korban-cetak/{siswa}', [ReportController::class, 'printKorban'])->name('guru.report.korban.print');
    Route::post('report-korban/{siswa}', [ReportController::class, 'printPdfKorban'])->name('guru.report.korban.pdf');
    Route::get('report-pelaku/{siswa}', [ReportController::class, 'pelaku'])->name('guru.report.pelaku');
    Route::post('report-pelaku-cetak/{siswa}', [ReportController::class, 'printPelaku'])->name('guru.report.pelaku.print');
    Route::post('report-pelaku/{siswa}', [ReportController::class, 'printPdfPelaku'])->name('guru.report.pelaku.pdf');

    Route::get('profil',[ProfilGuruController::class, 'index'])->name('guru.profil');
    Route::POST('profil',[ProfilGuruController::class, 'update'])->name('guru.profil.update');
    
});

// Siswa
route::post('siswa/formAngket',[FormAngketController::class, 'store'])->name('siswa.formAngket.store');
route::get('siswa/formAngket',[FormAngketController::class, 'index'])->name('siswa.formAngket');
route::get('siswa/hasilAngket',[FormAngketController::class,'hasil'])->name('siswa.hasilAngket');
route::get('siswa/hasilAngket1',[FormAngketController::class,'hasil1'])->name('siswa.hasilAngket1');
route::get('siswa/hasilAngket2',[FormAngketController::class,'hasil2'])->name('siswa.hasilAngket2');

