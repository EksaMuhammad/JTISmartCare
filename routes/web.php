<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\ArticleController;

Route::get('/', function () {
    return redirect()->route('landing');
});

// ===== HALAMAN AWAL =====
Route::get('/landing-page', function (){
    return view('page.landing');
})-> name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// ===== AUTENTIKASI =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ===== AREA USER =====
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard-first', function () {
        return view('dashboard.first-dashboard');
    })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Diagnosis
    Route::prefix('diagnosis')->name('diagnosis.')->group(function () {
        Route::get('/form', [DiagnosisController::class, 'form'])->name('form');
        Route::post('/proses', [DiagnosisController::class, 'proses'])->name('proses');
        Route::get('/hasil', [DiagnosisController::class, 'hasil'])->name('hasil');
        Route::get('/rekomendasi/{id}', [DiagnosisController::class, 'rekomendasi'])->name('rekomendasi');
        Route::get('/cetak/{id}', [DiagnosisController::class, 'exportPdf'])->name('cetak');
    });

    // Riwayat Diagnosis
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    // Knowledge Base
    Route::get('/knowledge', [KnowledgeController::class, 'index'])->name('knowledge.index');

    // Artikel
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/{slug}', [ArticleController::class, 'show'])->name('show');
    });

});