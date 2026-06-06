<?php
// Simulate EDAS calculation with different inputs

function hitungEdas($persenScores) {
    $aspek = [
        'openness' => ['persen' => $persenScores['O'], 'bobot' => 0.15],
        'conscientiousness' => ['persen' => $persenScores['C'], 'bobot' => 0.25],
        'extraversion' => ['persen' => $persenScores['E'], 'bobot' => 0.20],
        'agreeableness' => ['persen' => $persenScores['A'], 'bobot' => 0.15],
        'neuroticism' => ['persen' => $persenScores['N'], 'bobot' => 0.25],
    ];

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
            'sp'     => $sp,
            'sn'     => $sn,
        ];
    }

    $maxSp = max(array_column($ranking, 'sp')) ?: 1;
    $maxSn = max(array_column($ranking, 'sn')) ?: 1;

    foreach ($ranking as $kodeAlternatif => $data) {
        $nsp = $data['sp'] / $maxSp;
        $nsn = 1 - ($data['sn'] / $maxSn);

        $ranking[$kodeAlternatif]['as']   = ($nsp + $nsn) / 2;
        $ranking[$kodeAlternatif]['skor'] = round($ranking[$kodeAlternatif]['as'] * 100, 2);
    }

    uasort($ranking, fn ($a, $b) => $b['as'] <=> $a['as']);
    return $ranking;
}

echo "=== CASE 1: Low Risk (High O/C/E/A, Low N) ===\n";
$lowRiskInput = ['O' => 75, 'C' => 85, 'E' => 70, 'A' => 75, 'N' => 15];
print_r(hitungEdas($lowRiskInput));

echo "\n=== CASE 2: Medium Risk (Near 50% all traits) ===\n";
$medRiskInput = ['O' => 52, 'C' => 58, 'E' => 48, 'A' => 56, 'N' => 52];
print_r(hitungEdas($medRiskInput));

echo "\n=== CASE 3: High Risk (Low O/C/E/A, High N) ===\n";
$highRiskInput = ['O' => 35, 'C' => 20, 'E' => 30, 'A' => 30, 'N' => 90];
print_r(hitungEdas($highRiskInput));
