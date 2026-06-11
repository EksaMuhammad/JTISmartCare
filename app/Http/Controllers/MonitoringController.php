<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiagnosisSession;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $query = DiagnosisSession::with('user');

        // Search Nama / NIM
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Filter tingkat burnout
        if ($request->filled('tingkat')) {

            switch ($request->tingkat) {

                case 'Ringan':
                    $query->where('kategori_risiko', 'RISIKO RENDAH');
                    break;

                case 'Sedang':
                    $query->where('kategori_risiko', 'RISIKO SEDANG');
                    break;

                case 'Tinggi':
                    $query->where('kategori_risiko', 'RISIKO TINGGI');
                    break;
            }
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {

            $dates = explode(' - ', $request->tanggal);

            if (count($dates) === 2) {

                try {

                    $startDate = Carbon::createFromFormat(
                        'd/m/Y',
                        trim($dates[0])
                    )->startOfDay();

                    $endDate = Carbon::createFromFormat(
                        'd/m/Y',
                        trim($dates[1])
                    )->endOfDay();

                    $query->whereBetween(
                        'created_at',
                        [$startDate, $endDate]
                    );

                } catch (\Exception $e) {
                    // ignore
                }
            }
        }

        $data = $query->latest()->get();

        return view('admin.monitoring', compact('data'));
    }

    /**
     * EXPORT PDF
     */
    public function exportPdf(Request $request)
    {
        $query = DiagnosisSession::with('user');

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('user', function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");

            });
        }

        // Filter tingkat
        if ($request->filled('tingkat')) {

            switch ($request->tingkat) {

                case 'Ringan':
                    $query->where('kategori_risiko', 'RISIKO RENDAH');
                    break;

                case 'Sedang':
                    $query->where('kategori_risiko', 'RISIKO SEDANG');
                    break;

                case 'Tinggi':
                    $query->where('kategori_risiko', 'RISIKO TINGGI');
                    break;
            }
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {

            $dates = explode(' - ', $request->tanggal);

            if (count($dates) === 2) {

                try {

                    $startDate = Carbon::createFromFormat(
                        'd/m/Y',
                        trim($dates[0])
                    )->startOfDay();

                    $endDate = Carbon::createFromFormat(
                        'd/m/Y',
                        trim($dates[1])
                    )->endOfDay();

                    $query->whereBetween(
                        'created_at',
                        [$startDate, $endDate]
                    );

                } catch (\Exception $e) {
                    // ignore
                }
            }
        }

        $data = $query->latest()->get();

        $pdf = Pdf::loadView(
            'admin.monitoring_pdf',
            compact('data')
        );

        return $pdf->download(
            'laporan-monitoring.pdf'
        );
    }
}
