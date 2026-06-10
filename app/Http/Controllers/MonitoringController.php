<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diagnosis;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\DiagnosisSession::with('user');

        // Search by user name or NIM
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Filter by tingkat (kategori_risiko)
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

        // Filter by date range (e.g. "01/05/2026 - 31/05/2026")
        if ($request->filled('tanggal')) {
            $tanggal = $request->input('tanggal');
            $dates = explode(' - ', $tanggal);
            if (count($dates) === 2) {
                try {
                    $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                } catch (\Exception $e) {
                    // Ignore date parsing error
                }
            }
        }

        $data = $query->latest()->get();

        return view('admin.monitoring', compact('data'));
    }
}