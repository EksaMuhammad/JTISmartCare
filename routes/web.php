<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\MonitoringController;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DiagnosisSession;

Route::get('/', function () {
    return redirect()->route('landing');
});

// ===== HALAMAN AWAL =====
Route::get('/landing-page', function (){
    return view('page.landing');
})-> name('landing');


// ===== AUTENTIKASI =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/kuesioner-dummy', function () {
    return view('user.isi_kuesioner_dummy');
})->name('kuesioner.dummy');

// ===== AREA USER =====
Route::middleware(['auth'])->group(function () {
    

    // Dashboard
    Route::get('/dashboard-first', function () {
    return view('dashboard.first-dashboard');
})->name('dashboard.first');
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
    Route::get('/detail/{id}', [DiagnosisController::class, 'detail'])->name('detail'); 
});

// Riwayat Diagnosis
Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
Route::delete('/history/{id}', [HistoryController::class, 'destroy'])->name('history.destroy'); 

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

        Route::get('/monitoring/export-pdf', [MonitoringController::class, 'exportPdf'])
    ->name('monitoring.export.pdf');
        
        // ===== ADMIN KNOWLEDGE BASE =====
        Route::get('/knowledge', [KnowledgeController::class, 'index'])->name('knowledge.index');
        Route::post('/knowledge/store', [KnowledgeController::class, 'store'])->name('knowledge.store');
        Route::post('/knowledge/{id}/update', [KnowledgeController::class, 'update'])->name('knowledge.update');
        Route::delete('/knowledge/{id}/delete', [KnowledgeController::class, 'destroy'])->name('knowledge.destroy');
    });
    public function exportPdf(Request $request)
{
    $query = DiagnosisSession::with('user');

    if ($request->filled('search')) {
        $search = $request->input('search');

        $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('nim', 'like', "%{$search}%");
        });
    }

    if ($request->filled('tingkat')) {
        $tingkat = $request->input('tingkat');

        if ($tingkat === 'Ringan') {
            $query->where('kategori_risiko', 'RISIKO RENDAH');
        } elseif ($tingkat === 'Sedang') {
            $query->where('kategori_risiko', 'RISIKO SEDANG');
        } elseif ($tingkat === 'Tinggi') {
            $query->where('kategori_risiko', 'RISIKO TINGGI');
        }
    }

    $data = $query->latest()->get();

    $pdf = Pdf::loadView(
        'admin.monitoring_pdf',
        compact('data')
    )->setPaper('a4', 'landscape');

    return $pdf->download(
        'laporan-monitoring-burnout.pdf'
    );
}
    
});
