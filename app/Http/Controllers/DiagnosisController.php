<?php

// ============================================================
// STEP 3 - CONTROLLER
// FILE: app/Http/Controllers/DiagnosisController.php
// CARA: GANTI SELURUH isi file yang lama dengan kode ini
// ============================================================

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\GeminiAIHelper;
use App\Models\DiagnosisSession;

class DiagnosisController extends Controller
{
    // ============================================================
    // FORM KUESIONER
    // ============================================================
    public function form()
    {
        return view('user.isikuesioner');
    }

    // ============================================================
    // PROSES JAWABAN → HITUNG → SIMPAN KE DB → REDIRECT HASIL
    // ============================================================
    public function proses(Request $request)
    {
            // Jika belum login
        if (!auth()->check()) {

            // simpan jawaban sementara
            session([
                'guest_jawaban' => $request->jawaban,
                'after_login_redirect' => 'diagnosis.resume'
            ]);

            return redirect()->route('login')
                ->with('warning', 'Silakan login atau daftar terlebih dahulu untuk melihat hasil diagnosis.');
        }

        $request->validate([
            'jawaban'   => ['required', 'array', 'size:20'],
            'jawaban.*' => ['required', 'integer', 'between:1,5'],
        ]);

        $jawaban = array_map('intval', $request->jawaban);

        // LOG INPUT UNTUK DEBUGGING
        \Log::info('Jawaban Kuesioner User:', $jawaban);

        // HITUNG OCEAN + EDAS
        $hasil = $this->hitungEdasOcean($jawaban);

        // LOG HASIL PERHITUNGAN
        \Log::info('Hasil Perhitungan EDAS:', [
            'risk_index' => $hasil['risk_index'],
            'kategori' => $hasil['kategori'],
            'aspek' => array_map(fn($a) => ['persen' => $a['persen']], $hasil['aspek']),
        ]);

        // REKOMENDASI AI (Gemini)
        $aiData = [
            'total_skor'      => $hasil['risk_index'],
            'jawaban'         => $jawaban,
            'aspek_psikologi' => $hasil['aspek'],
            'kategori_risiko' => $hasil['kategori'],
            'edas'            => $hasil['edas'],
        ];
        $aiRekomendasi = GeminiAIHelper::getRecommendation($aiData);

        // ✅ SIMPAN KE DATABASE
        $session = DiagnosisSession::create([
            'user_id'         => auth()->id(),
            'jawaban'         => $jawaban,
            'aspek_psikologi' => $hasil['aspek'],
            'edas_result'     => $hasil['edas'],
            'risk_index'      => $hasil['risk_index'],
            'total_skor'      => $hasil['risk_index'],
            'kategori_risiko' => $hasil['kategori'],
            'rule_terpilih'   => $hasil['edas']['terbaik']['label'] ?? null,
            'rekomendasi'     => $aiRekomendasi ?? '-',
            'rekomendasi_ai'  => $aiRekomendasi ?? '-',
        ]);

        // Simpan ke session (termasuk ID untuk halaman hasil)
        session([
            'diagnosis_id'    => $session->id,
            'total_skor'      => $hasil['risk_index'],
            'jawaban'         => $jawaban,
            'aspek_psikologi' => $hasil['aspek'],
            'kategori_risiko' => $hasil['kategori'],
            'edas'            => $hasil['edas'],
            'aiRekomendasi'   => $aiRekomendasi,
            'waktu'           => now(),
        ]);

        return redirect()->route('diagnosis.hasil');
    }

    // ============================================================
    // TAMPILKAN HASIL (dari session)
    // ============================================================
    public function hasil()
    {
        $total          = session('total_skor');
        $jawaban        = session('jawaban');
        $waktu          = session('waktu');
        $aspekPsikologi = session('aspek_psikologi');
        $kategoriRisiko = session('kategori_risiko');
        $edas           = session('edas');
        $aiRekomendasi  = session('aiRekomendasi');

        if ($total === null || !$jawaban) {
            return redirect()->route('diagnosis.form');
        }

        return view('user.hasildiagnosis', [
            'jawaban'        => $jawaban,
            'total'          => $total,
            'aspekPsikologi' => $aspekPsikologi,
            'kategoriRisiko' => $kategoriRisiko,
            'edas'           => $edas,
            'waktu'          => $waktu,
            'aiRekomendasi'  => $aiRekomendasi,
        ]);
    }

    // ============================================================
    // TAMPILKAN DETAIL RIWAYAT (dari database, bukan session)
    // Dipakai di halaman riwayat ketika klik salah satu riwayat
    // ============================================================
    public function detail($id)
    {
        // Ambil dari DB, pastikan milik user yang login
        $session = DiagnosisSession::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('user.hasildiagnosis', [
            'jawaban'        => $session->jawaban,
            'total'          => $session->risk_index,
            'aspekPsikologi' => $session->aspek_psikologi,
            'kategoriRisiko' => $session->kategori_risiko,
            'edas'           => $session->edas_result,
            'waktu'          => $session->created_at,
            'aiRekomendasi'  => $session->rekomendasi_ai,
        ]);
    }

    public function resume()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $jawaban = session('guest_jawaban');

        if (!$jawaban) {
            return redirect()->route('diagnosis.form');
        }

        $jawaban = array_map('intval', $jawaban);

        $hasil = $this->hitungEdasOcean($jawaban);

        $aiRekomendasi = \App\Helpers\GeminiAIHelper::getRecommendation([
            'total_skor' => $hasil['risk_index'],
            'jawaban' => $jawaban,
            'aspek_psikologi' => $hasil['aspek'],
            'kategori_risiko' => $hasil['kategori'],
            'edas' => $hasil['edas'],
        ]);

        $session = \App\Models\DiagnosisSession::create([
            'user_id' => auth()->id(),
            'jawaban' => $jawaban,
            'aspek_psikologi' => $hasil['aspek'],
            'edas_result' => $hasil['edas'],
            'risk_index' => $hasil['risk_index'],
            'total_skor' => $hasil['risk_index'],
            'kategori_risiko' => $hasil['kategori'],
            'rekomendasi_ai' => $aiRekomendasi,
        ]);

        session([
            'diagnosis_id' => $session->id,
            'total_skor' => $hasil['risk_index'],
            'jawaban' => $jawaban,
            'aspek_psikologi' => $hasil['aspek'],
            'kategori_risiko' => $hasil['kategori'],
            'edas' => $hasil['edas'],
            'aiRekomendasi' => $aiRekomendasi,
            'waktu' => now(),
        ]);

        session()->forget('guest_jawaban');

        return redirect()->route('diagnosis.hasil');
    }

    public function rekomendasi($id)
    {
        return "Halaman rekomendasi ID: " . $id;
    }

    public function exportPdf($id)
    {
        return "Export PDF ID: " . $id;
    }

    // ============================================================
    // HITUNG OCEAN + EDAS (tidak berubah dari versi lama)
    // ============================================================
    private function hitungEdasOcean(array $jawaban): array
    {
        $aspek = [
            'openness' => [
                'label'     => 'Openness',
                'items'     => [
                    ['index' => 16, 'reverse' => false],
                    ['index' => 17, 'reverse' => true],
                    ['index' => 18, 'reverse' => false],
                    ['index' => 19, 'reverse' => false],
                ],
                'bobot'     => 0.15,
                'warna'     => '#0ea5e9',
                'kode'      => 'O',
                'deskripsi' => 'Keterbukaan terhadap pengalaman dan ide baru.',
            ],
            'conscientiousness' => [
                'label'     => 'Conscientiousness',
                'items'     => [
                    ['index' => 8, 'reverse' => false],
                    ['index' => 9, 'reverse' => true],
                    ['index' => 10, 'reverse' => false],
                    ['index' => 11, 'reverse' => true],
                ],
                'bobot'     => 0.25,
                'warna'     => '#ef4444',
                'kode'      => 'C',
                'deskripsi' => 'Kedisiplinan, keteraturan, dan kontrol diri.',
            ],
            'extraversion' => [
                'label'     => 'Extraversion',
                'items'     => [
                    ['index' => 0, 'reverse' => false],
                    ['index' => 1, 'reverse' => true],
                    ['index' => 2, 'reverse' => false],
                    ['index' => 3, 'reverse' => true],
                ],
                'bobot'     => 0.20,
                'warna'     => '#eab308',
                'kode'      => 'E',
                'deskripsi' => 'Energi sosial dan kecenderungan berinteraksi.',
            ],
            'agreeableness' => [
                'label'     => 'Agreeableness',
                'items'     => [
                    ['index' => 4, 'reverse' => false],
                    ['index' => 5, 'reverse' => true],
                    ['index' => 6, 'reverse' => false],
                    ['index' => 7, 'reverse' => true],
                ],
                'bobot'     => 0.15,
                'warna'     => '#22c55e',
                'kode'      => 'A',
                'deskripsi' => 'Kooperatif, empatik, dan mudah bekerja sama.',
            ],
            'neuroticism' => [
                'label'     => 'Neuroticism',
                'items'     => [
                    ['index' => 12, 'reverse' => false],
                    ['index' => 13, 'reverse' => true],
                    ['index' => 14, 'reverse' => false],
                    ['index' => 15, 'reverse' => true],
                ],
                'bobot'     => 0.25,
                'warna'     => '#a855f7',
                'kode'      => 'N',
                'deskripsi' => 'Kerentanan terhadap cemas, tegang, dan emosi negatif.',
            ],
        ];

        foreach ($aspek as $key => $data) {
            $raw = array_sum(array_map(function ($item) use ($jawaban) {
                $nilai = $jawaban[$item['index']] ?? 1;
                return $item['reverse'] ? 6 - $nilai : $nilai;
            }, $data['items']));

            $min = count($data['items']);
            $max = count($data['items']) * 5;

            $aspek[$key]['skor']   = $raw;
            $aspek[$key]['persen'] = round((($raw - $min) / ($max - $min)) * 100);
        }

        $riskIndex = $this->hitungIndeksRisikoOcean($aspek);
        $edas      = $this->prosesEdas($aspek);

        return [
            'risk_index' => $riskIndex,
            'kategori'   => $edas['terbaik']['label'],
            'aspek'      => $aspek,
            'edas'       => $edas,
        ];
    }

    private function prosesEdas(array $aspek): array
    {
        $profilPakar = [
            'rendah' => [
                'label'  => 'RISIKO RENDAH',
                'target' => [
                    'openness'          => 65,
                    'conscientiousness' => 80,
                    'extraversion'      => 65,
                    'agreeableness'     => 70,
                    'neuroticism'       => 20,
                ],
            ],
            'sedang' => [
                'label'  => 'RISIKO SEDANG',
                'target' => [
                    'openness'          => 55,
                    'conscientiousness' => 55,
                    'extraversion'      => 50,
                    'agreeableness'     => 55,
                    'neuroticism'       => 55,
                ],
            ],
            'tinggi' => [
                'label'  => 'RISIKO TINGGI',
                'target' => [
                    'openness'          => 40,
                    'conscientiousness' => 25,
                    'extraversion'      => 35,
                    'agreeableness'     => 35,
                    'neuroticism'       => 85,
                ],
            ],
        ];

        $matrix = [];
        foreach ($profilPakar as $kodeAlternatif => $profil) {
            foreach ($aspek as $kodeKriteria => $kriteria) {
                $selisih = abs($kriteria['persen'] - $profil['target'][$kodeKriteria]);
                $matrix[$kodeAlternatif][$kodeKriteria] = max(0, 100 - $selisih);
            }
        }

        $average = [];
        foreach (array_keys($aspek) as $kodeKriteria) {
            $nilaiKriteria           = array_column($matrix, $kodeKriteria);
            $average[$kodeKriteria]  = array_sum($nilaiKriteria) / count($nilaiKriteria);
        }

        $ranking = [];
        foreach ($matrix as $kodeAlternatif => $nilaiAlternatif) {
            $sp = 0;
            $sn = 0;

            foreach ($nilaiAlternatif as $kodeKriteria => $nilai) {
                $avg   = max($average[$kodeKriteria], 0.0001);
                $bobot = $aspek[$kodeKriteria]['bobot'];

                $pda = max(0, ($nilai - $avg) / $avg);
                $nda = max(0, ($avg - $nilai) / $avg);

                $sp += $bobot * $pda;
                $sn += $bobot * $nda;
            }

            $ranking[$kodeAlternatif] = [
                'label'  => $profilPakar[$kodeAlternatif]['label'],
                'target' => $profilPakar[$kodeAlternatif]['target'],
                'sp'     => $sp,
                'sn'     => $sn,
            ];
        }

        $maxSp = max(array_column($ranking, 'sp')) ?: 1;
        $maxSn = max(array_column($ranking, 'sn')) ?: 1;

        foreach ($ranking as $kodeAlternatif => $data) {
            $nsp = $data['sp'] / $maxSp;
            $nsn = 1 - ($data['sn'] / $maxSn);

            $ranking[$kodeAlternatif]['nsp']  = $nsp;
            $ranking[$kodeAlternatif]['nsn']  = $nsn;
            $ranking[$kodeAlternatif]['as']   = ($nsp + $nsn) / 2;
            $ranking[$kodeAlternatif]['skor'] = round($ranking[$kodeAlternatif]['as'] * 100, 2);
        }

        uasort($ranking, fn ($a, $b) => $b['as'] <=> $a['as']);

        return [
            'average_solution' => $average,
            'matrix'           => $matrix,
            'ranking'          => $ranking,
            'terbaik'          => reset($ranking),
        ];
    }

    private function hitungIndeksRisikoOcean(array $aspek): int
    {
        $risk = (
            (100 - $aspek['conscientiousness']['persen']) * 0.25 +
            (100 - $aspek['extraversion']['persen'])      * 0.15 +
            (100 - $aspek['agreeableness']['persen'])     * 0.10 +
            (100 - $aspek['openness']['persen'])          * 0.05 +
            $aspek['neuroticism']['persen']               * 0.45
        );

        return (int) round($risk);
    }
}