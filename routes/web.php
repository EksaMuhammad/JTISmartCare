<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\MonitoringController;

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

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard');

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
    // Route::get('/knowledge', [KnowledgeController::class, 'index'])->name('knowledge.index');
    
    // Artikel
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('index');
        Route::get('/{slug}', [ArticleController::class, 'show'])->name('show');
    });

    // ===== ADMIN ARTICLE =====
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/articles', [ArticleController::class, 'index'])
            ->name('articles');
        
        Route::post('/articles/store', [ArticleController::class, 'store']
            )->name('articles.store');
        
        Route::get('/monitoring', [MonitoringController::class, 'index'])
            ->name('monitoring');
        
        // ===== ADMIN KNOWLEDGE BASE =====
        Route::get('/knowledge', [KnowledgeController::class, 'index'])->name('knowledge.index');
        Route::post('/knowledge/store', [KnowledgeController::class, 'store'])->name('knowledge.store');
        Route::post('/knowledge/{id}/update', [KnowledgeController::class, 'update'])->name('knowledge.update');
        Route::delete('/knowledge/{id}/delete', [KnowledgeController::class, 'destroy'])->name('knowledge.destroy');
    });
});