<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diagnosis;
use App\Models\User;
use App\Models\DiagnosisSession;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Dashboard mahasiswa pertama login (jika belum pernah mengisi kuesioner)
        if (!$user->diagnosisSessions()->exists()) {
            return view('dashboard.first-dashboard', compact('user'));
        }

        $latestDiagnosis = $user->diagnosisSessions()->latest()->first();
        $totalDiagnosis = $user->diagnosisSessions()->count();
        $riwayat = $user->diagnosisSessions()->latest()->take(5)->get();
        $articles = [];

        return view('dashboard.index', compact(
            'user',
            'latestDiagnosis',
            'totalDiagnosis',
            'riwayat',
            'articles'
        ));
    }

    // ===== DASHBOARD ADMIN =====
    public function admin()
    {
        // Semua data diagnosis dari sesi diagnosis
        $data = DiagnosisSession::with('user')->latest()->get();

        // Total mahasiswa (dengan role 'mahasiswa')
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();

        // Total diagnosis
        $totalDiagnosis = DiagnosisSession::count();

        // Burnout tinggi
        $burnoutTinggi = DiagnosisSession::where(
            'kategori_risiko',
            'RISIKO TINGGI'
        )->count();

        // Rata-rata skor (risk_index) diubah ke skala 10
        $rataRata = DiagnosisSession::avg('risk_index') / 10 ?? 0;

        return view('admin.dashboard', compact(
            'totalMahasiswa',
            'totalDiagnosis',
            'burnoutTinggi',
            'rataRata',
            'data'
        ));
    }
}