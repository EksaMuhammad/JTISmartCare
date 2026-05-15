<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // 1. Cek Kondisi: Jika user BELUM mengisi form diagnosis sama sekali
        if (!$user->is_completed) {
            // Langsung arahkan ke view dashboard khusus pengguna yang baru login
            return view('dashboard.first-dashboard', compact('user'));
        }

        // Data sementara agar view tidak error
        $latestDiagnosis = null;
        $totalDiagnosis = 0;
        $riwayat = [];
        $articles = [];

        return view('dashboard.index', compact('user', 'latestDiagnosis', 'totalDiagnosis', 'riwayat', 'articles'));
    }
}
