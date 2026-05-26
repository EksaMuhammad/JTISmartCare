<?php

// ============================================================
// STEP 4 - CONTROLLER
// FILE: app/Http/Controllers/HistoryController.php
// CARA: GANTI SELURUH isi file yang lama dengan kode ini
// ============================================================

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiagnosisSession;

class HistoryController extends Controller
{
    // ============================================================
    // DAFTAR SEMUA RIWAYAT MILIK USER YANG LOGIN
    // ============================================================
    public function index()
    {
        $riwayat = DiagnosisSession::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')  // terbaru di atas
            ->get();

        return view('user.riwayatdiagnosis', compact('riwayat'));
    }

    // ============================================================
    // HAPUS SATU RIWAYAT
    // ============================================================
    public function destroy($id)
    {
        $session = DiagnosisSession::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $session->delete();

        return redirect()->route('history.index')
            ->with('success', 'Riwayat diagnosis berhasil dihapus.');
    }
}