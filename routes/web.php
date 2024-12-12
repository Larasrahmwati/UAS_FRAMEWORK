<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Halaman dashboard dengan middleware auth dan verified
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Grup rute dengan middleware auth
Route::middleware('auth')->group(function () {
    // Rute untuk profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Rute untuk manajemen mahasiswa
    Route::get('mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index'); // Untuk halaman daftar mahasiswa
    Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store'); // Menambahkan mahasiswa
    Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy'); // Menghapus mahasiswa
    Route::get('/export-mahasiswa', [MahasiswaController::class, 'export'])->name('mahasiswa.export'); // Export data mahasiswa
});

// Mengimpor rute otentikasi
require __DIR__.'/auth.php';
