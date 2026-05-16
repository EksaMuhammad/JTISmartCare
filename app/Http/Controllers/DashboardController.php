<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diagnosis;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Dashboard mahasiswa pertama login
        if (!$user->is_completed) {

            return view('dashboard.first-dashboard', compact('user'));
        }

        // Data sementara agar view tidak error
        $latestDiagnosis = null;
        $totalDiagnosis = 0;
        $riwayat = [];
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
        // Semua data diagnosis
        $data = Diagnosis::latest()->get();

        // Total mahasiswa
        $totalMahasiswa = User::where('role', 'user')->count();

        // Total diagnosis
        $totalDiagnosis = Diagnosis::count();

        // Burnout tinggi
        $burnoutTinggi = Diagnosis::where(
            'tingkat_burnout',
            'Tinggi'
        )->count();

        // Rata-rata skor
        $rataRata = Diagnosis::avg('hasil') ?? 0;

        return view('admin.dashboard', compact(
            'totalMahasiswa',
            'totalDiagnosis',
            'burnoutTinggi',
            'rataRata',
            'data'
        ));
    }
}