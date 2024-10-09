<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\KonfigurasiController;


// Routes untuk user yang belum login (guest)
date_default_timezone_set('Asia/Makassar');

Route::middleware('guest:karyawan')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/proseslogin', [AuthController::class, 'proseslogin'])->name('proses.login');
});

// Routes untuk admin yang belum login
Route::middleware('guest:user')->group(function () {
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('admin.login');

    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin'])->name('admin.proses.login');
});

// Routes untuk user yang sudah login (authenticated)
Route::middleware('auth:karyawan')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/proseslogout', [AuthController::class, 'proseslogout'])->name('logout');

});
    // Presensi Routes
    Route::middleware('auth:karyawan')->prefix('presensi')->group(function () {
        Route::get('/create', [PresensiController::class, 'create'])->name('presensi.create');
        Route::post('/store', [PresensiController::class, 'store'])->name('presensi.store');
        Route::get('/histori', [PresensiController::class, 'histori'])->name('presensi.histori');
        Route::post('/gethistori', [PresensiController::class, 'gethistori'])->name('presensi.gethistori');
        Route::get('/izin', [PresensiController::class, 'izin'])->name('presensi.izin');
        Route::get('/buatizin', [PresensiController::class, 'buatizin'])->name('presensi.buatizin');
        Route::post('/storeizin', [PresensiController::class, 'storeIzin'])->name('storeizin');
        Route::post('/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin'])->name('presensi.cekpengajuanizin');
    });

    // Profile Routes
    Route::get('/editprofile', [PresensiController::class, 'editprofile'])->name('editprofile');
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateprofile'])->name('presensi.updateprofile');


    // Middleware untuk memastikan admin sudah login
Route::middleware('auth:user')->group(function () {
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin'])->name('admin.dashboard');

    //karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::post('/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store'); // Menambahkan nama untuk rute store
    Route::get('/karyawan/{nik}/edit', [KaryawanController::class, 'edit'])->name('karyawan.edit'); // Ubah menjadi GET untuk halaman edit
    Route::put('/karyawan/{nik}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::delete('/karyawan/{nik}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    
    // Route untuk logout
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin'])->name('logoutadmin');

    
    //departemen
    // Rute untuk Menampilkan Daftar Departemen
    Route::get('/departemen', [DepartemenController::class, 'index'])->name('departemen.index');
    
    // Rute untuk Menyimpan Departemen Baru
    Route::post('/departemen/store', [DepartemenController::class, 'store'])->name('departemen.store');
    
    // Rute untuk Menghapus Departemen
    Route::delete('/departemen/{kode_dept}', [DepartemenController::class, 'destroy'])->name('departemen.destroy');
    
    // Rute untuk Edit Departemen (Jika Diperlukan)
    Route::get('/departemen/edit', [DepartemenController::class, 'edit'])->name('departemen.edit');
    
    // Rute untuk Update Departemen (Jika Diperlukan)
    Route::put('/departemen/{kode_dept}', [DepartemenController::class, 'update'])->name('departemen.update');
    
    //presensi/monitoring
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::Class, 'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::Class, 'tampilkanpeta']);
    Route::get('/presensi/laporan', [PresensiController::Class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::Class, 'cetaklaporan']);
    Route::get('/presensi/rekap', [PresensiController::Class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap'])->name('presensi.cetakrekap');
    Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit'])->name('presensi.izinsakit');
    Route::post('/presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit'])->name('presensi.approveizinsakit');
    Route::post('/presensi/batalizinsakit', [PresensiController::class, 'batalizinsakit'])->name('batalizinsakit');


    // konfigurasi
    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class, 'lokasikantor']);
    Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);

}); 