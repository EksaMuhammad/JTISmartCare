<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\GeminiAIHelper;

class DiagnosisController extends Controller
{
    public function form()
    {
        return view('user.isikuesioner');
    }

    public function proses(Request $request)
    {
        $request->validate([
            'jawaban' => ['required', 'array', 'size:10'],
            'jawaban.*' => ['required', 'integer', 'between:1,5'],
        ]);

        $jawaban = array_map('intval', $request->jawaban);
        $hasil = $this->hitungEdasOcean($jawaban);

        session([
            'total_skor' => $hasil['risk_index'],
            'jawaban' => $jawaban,
            'aspek_psikologi' => $hasil['aspek'],
            'kategori_risiko' => $hasil['kategori'],
            'edas' => $hasil['edas'],
            'waktu' => now(),
        ]);

        return redirect()->route('diagnosis.hasil');
    }

    public function hasil()
    {
        $total = session('total_skor');
        $jawaban = session('jawaban');
        $waktu = session('waktu');

        if ($total === null || !$jawaban) {
            return redirect()->route('diagnosis.form');
        }

        $aspekPsikologi = session('aspek_psikologi');
        $kategoriRisiko = session('kategori_risiko');
        $edas = session('edas');

        // Compose data for Gemini
        $aiData = [
            'total_skor' => $total,
            'jawaban' => $jawaban,
            'aspek_psikologi' => $aspekPsikologi,
            'kategori_risiko' => $kategoriRisiko,
            'edas' => $edas,
        ];
        $aiRekomendasi = GeminiAIHelper::getRecommendation($aiData);

        return view('user.hasildiagnosis', [
            'jawaban' => $jawaban,
            'total' => $total,
            'aspekPsikologi' => $aspekPsikologi,
            'kategoriRisiko' => $kategoriRisiko,
            'edas' => $edas,
            'waktu' => $waktu,
            'aiRekomendasi' => $aiRekomendasi,
        ]);
    }

    public function rekomendasi($id)
    {
        return "Halaman rekomendasi ID: " . $id;
    }

    public function exportPdf($id)
    {
        return "Export PDF ID: " . $id;
    }

    /**
     * Perhitungan OCEAN (Big Five) menggunakan rata-rata skor dinormalisasi 0-100
     * Referensi: John, O. P., & Srivastava, S. (1999). Handbook of personality: Theory and research. https://pages.ucsd.edu/~mboyle/COGS260/JohnSrivastava1999.pdf
     */
    private function hitungEdasOcean(array $jawaban): array
    {
        $aspek = [
            'openness' => [
                'label' => 'Openness',
                'items' => [
                    ['index' => 4, 'reverse' => false],
                    ['index' => 9, 'reverse' => true],
                ],
                'bobot' => 0.15,
                'warna' => '#0ea5e9',
                'kode' => 'O',
            ],
            'conscientiousness' => [
                'label' => 'Conscientiousness',
                'items' => [
                    ['index' => 2, 'reverse' => false],
                    ['index' => 7, 'reverse' => true],
                ],
                'bobot' => 0.25,
                'warna' => '#ef4444',
                'kode' => 'C',
            ],
            'extraversion' => [
                'label' => 'Extraversion',
                'items' => [
                    ['index' => 0, 'reverse' => false],
                    ['index' => 5, 'reverse' => true],
                ],
                'bobot' => 0.20,
                'warna' => '#eab308',
                'kode' => 'E',
            ],
            'agreeableness' => [
                'label' => 'Agreeableness',
                'items' => [
                    ['index' => 6, 'reverse' => false],
                    ['index' => 1, 'reverse' => true],
                ],
                'bobot' => 0.15,
                'warna' => '#22c55e',
                'kode' => 'A',
            ],
            'neuroticism' => [
                'label' => 'Neuroticism',
                'items' => [
                    ['index' => 3, 'reverse' => false],
                    ['index' => 8, 'reverse' => true],
                ],
                'bobot' => 0.25,
                'warna' => '#a855f7',
                'kode' => 'N',
            ],
        ];

        foreach ($aspek as $key => $data) {
            $scores = array_map(function ($item) use ($jawaban) {
                $nilai = $jawaban[$item['index']] ?? 1;
                return $item['reverse'] ? 6 - $nilai : $nilai;
            }, $data['items']);

            $avg = array_sum($scores) / count($scores);
            // Normalisasi ke 0–100
            $aspek[$key]['skor'] = $avg;
            $aspek[$key]['persen'] = round((($avg - 1) / 4) * 100);
        }

        $riskIndex = $this->hitungIndeksRisikoOcean($aspek);
        $edas = $this->prosesEdas($aspek);

        return [
            'risk_index' => $riskIndex,
            'kategori' => $edas['terbaik']['label'],
            'aspek' => $aspek,
            'edas' => $edas,
        ];
    }

    /**
     * Perhitungan EDAS (Evaluation based on Distance from Average Solution)
     * Referensi: Ghorabaee, M. K., et al. (2015). https://www.mii.lt/informatica/pdf/INFORMATICA2015_26(3)_435-451.pdf
     */
    private function prosesEdas(array $aspek): array
    {
        $profilPakar = [
            'rendah' => [
                'label' => 'RISIKO RENDAH',
                'target' => [
                    'openness' => 65,
                    'conscientiousness' => 80,
                    'extraversion' => 65,
                    'agreeableness' => 70,
                    'neuroticism' => 20,
                ],
            ],
            'sedang' => [
                'label' => 'RISIKO SEDANG',
                'target' => [
                    'openness' => 55,
                    'conscientiousness' => 55,
                    'extraversion' => 50,
                    'agreeableness' => 55,
                    'neuroticism' => 55,
                ],
            ],
            'tinggi' => [
                'label' => 'RISIKO TINGGI',
                'target' => [
                    'openness' => 40,
                    'conscientiousness' => 25,
                    'extraversion' => 35,
                    'agreeableness' => 35,
                    'neuroticism' => 85,
                ],
            ],
        ];

        // Matrix: menghitung tingkat kemiripan (similarity) antara skor user dengan target pakar
        $matrix = [];
        foreach ($profilPakar as $kodeAlternatif => $profil) {
            foreach ($aspek as $kodeKriteria => $kriteria) {
                $matrix[$kodeAlternatif][$kodeKriteria] = 100 - abs($kriteria['persen'] - $profil['target'][$kodeKriteria]);
            }
        }

        // Hitung rata-rata tiap kriteria
        $average = [];
        foreach (array_keys($aspek) as $kodeKriteria) {
            $nilaiKriteria = array_column($matrix, $kodeKriteria);
            $average[$kodeKriteria] = array_sum($nilaiKriteria) / count($nilaiKriteria);
        }

        // Hitung PDA dan NDA sesuai jurnal
        $ranking = [];
        foreach ($matrix as $kodeAlternatif => $nilaiAlternatif) {
            $sp = 0;
            $sn = 0;
            foreach ($nilaiAlternatif as $kodeKriteria => $nilai) {
                $avg = $average[$kodeKriteria];
                $bobot = $aspek[$kodeKriteria]['bobot'];
                $pda = max(0, $nilai - $avg);
                $nda = max(0, $avg - $nilai);
                
                // Pembagian dengan average (avg) sesuai dengan rumus standar jurnal EDAS (Ghorabaee et al., 2015)
                if ($avg > 0) {
                    $pda = $pda / $avg;
                    $nda = $nda / $avg;
                }
                
                $sp += $bobot * $pda;
                $sn += $bobot * $nda;
            }
            $ranking[$kodeAlternatif] = [
                'label' => $profilPakar[$kodeAlternatif]['label'],
                'sp' => $sp,
                'sn' => $sn,
            ];
        }

        $maxSp = max(array_column($ranking, 'sp')) ?: 1;
        $maxSn = max(array_column($ranking, 'sn')) ?: 1;

        foreach ($ranking as $kodeAlternatif => $data) {
            $nsp = $data['sp'] / $maxSp;
            $nsn = 1 - ($data['sn'] / $maxSn);
            $ranking[$kodeAlternatif]['as'] = ($nsp + $nsn) / 2;
            $ranking[$kodeAlternatif]['skor'] = round($ranking[$kodeAlternatif]['as'] * 100, 2);
        }

        uasort($ranking, fn ($a, $b) => $b['as'] <=> $a['as']);

        return [
            'average_solution' => $average,
            'matrix' => $matrix,
            'ranking' => $ranking,
            'terbaik' => reset($ranking),
        ];
    }

    private function hitungIndeksRisikoOcean(array $aspek): int
    {
        $risk = (
            (100 - $aspek['conscientiousness']['persen']) * 0.25 +
            (100 - $aspek['extraversion']['persen']) * 0.15 +
            (100 - $aspek['agreeableness']['persen']) * 0.10 +
            (100 - $aspek['openness']['persen']) * 0.05 +
            $aspek['neuroticism']['persen'] * 0.45
        );

        return (int) round($risk);
    }
}
