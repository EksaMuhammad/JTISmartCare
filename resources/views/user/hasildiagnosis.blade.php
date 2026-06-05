@extends('app')

@section('content')

@php
    $namaVariabel = [
        0  => ['label' => 'Mudah bergaul dan energik', 'dim' => 'E'],
        1  => ['label' => 'Kritis dan mudah mencari kesalahan', 'dim' => 'A'],
        2  => ['label' => 'Dapat diandalkan dan teratur', 'dim' => 'C'],
        3  => ['label' => 'Mudah cemas atau tegang', 'dim' => 'N'],
        4  => ['label' => 'Terbuka pada ide baru', 'dim' => 'O'],
        5  => ['label' => 'Pendiam dan menjaga jarak', 'dim' => 'E'],
        6  => ['label' => 'Empatik dan membantu orang lain', 'dim' => 'A'],
        7  => ['label' => 'Kurang teratur dan menunda pekerjaan', 'dim' => 'C'],
        8  => ['label' => 'Tenang dan mampu mengendalikan stres', 'dim' => 'N'],
        9  => ['label' => 'Kurang tertarik mencoba hal baru', 'dim' => 'O'],
    ];

    $dimColor = [
        'O' => ['bg' => '#0ea5e9', 'text' => '#0ea5e9', 'badge' => 'badge-dep', 'label' => 'Openness'],
        'C' => ['bg' => '#ef4444', 'text' => '#ef4444', 'badge' => 'badge-kel', 'label' => 'Conscientiousness'],
        'E' => ['bg' => '#eab308', 'text' => '#eab308', 'badge' => 'badge-pre', 'label' => 'Extraversion'],
        'A' => ['bg' => '#22c55e', 'text' => '#22c55e', 'badge' => 'badge-kel', 'label' => 'Agreeableness'],
        'N' => ['bg' => '#a855f7', 'text' => '#a855f7', 'badge' => 'badge-dep', 'label' => 'Neuroticism'],
    ];

    $jawaban = $jawaban ?? array_fill(0, 10, 1);

    $aspekPsikologi = $aspekPsikologi ?? [
        'openness' => ['label' => 'Openness', 'items' => [4, 9], 'bobot' => 0.15, 'warna' => '#0ea5e9', 'kode' => 'O'],
        'conscientiousness' => ['label' => 'Conscientiousness', 'items' => [2, 7], 'bobot' => 0.25, 'warna' => '#ef4444', 'kode' => 'C'],
        'extraversion' => ['label' => 'Extraversion', 'items' => [0, 5], 'bobot' => 0.20, 'warna' => '#eab308', 'kode' => 'E'],
        'agreeableness' => ['label' => 'Agreeableness', 'items' => [1, 6], 'bobot' => 0.15, 'warna' => '#22c55e', 'kode' => 'A'],
        'neuroticism' => ['label' => 'Neuroticism', 'items' => [3, 8], 'bobot' => 0.25, 'warna' => '#a855f7', 'kode' => 'N'],
    ];

    $defaultWarna = [
        'openness' => '#0ea5e9',
        'conscientiousness' => '#ef4444',
        'extraversion' => '#eab308',
        'agreeableness' => '#22c55e',
        'neuroticism' => '#a855f7',
    ];
    $defaultKode = [
        'openness' => 'O',
        'conscientiousness' => 'C',
        'extraversion' => 'E',
        'agreeableness' => 'A',
        'neuroticism' => 'N',
    ];

    foreach ($aspekPsikologi as $key => $data) {
        if (!isset($aspekPsikologi[$key]['warna'])) {
            $aspekPsikologi[$key]['warna'] = $defaultWarna[$key] ?? '#64748b';
        }
        if (!isset($aspekPsikologi[$key]['kode'])) {
            $aspekPsikologi[$key]['kode'] = $defaultKode[$key] ?? strtoupper(substr($key, 0, 1));
        }
        if (!isset($aspekPsikologi[$key]['persen'])) {
            $raw = array_sum(array_map(fn($index) => $jawaban[$index] ?? 1, $data['items']));
            $min = count($data['items']);
            $max = count($data['items']) * 5;
            $aspekPsikologi[$key]['skor'] = $raw;
            $aspekPsikologi[$key]['persen'] = round((($raw - $min) / ($max - $min)) * 100);
        }
    }

    $total = $total ?? round(array_sum(array_column($aspekPsikologi, 'persen')) / count($aspekPsikologi));
    $cf = round($total / 100, 2);
    $cfPct = $total;

    $risikoLabel = $kategoriRisiko ?? match (true) {
        $total >= 70 => 'RISIKO TINGGI',
        $total >= 40 => 'RISIKO SEDANG',
        default => 'RISIKO RENDAH',
    };

    $risikoClass = match ($risikoLabel) {
        'RISIKO TINGGI' => 'badge-risiko-tinggi',
        'RISIKO SEDANG' => 'badge-risiko-sedang',
        default => 'badge-risiko-rendah',
    };

    $edas = $edas ?? null;
    $edasScore = $edas['terbaik']['skor'] ?? null;

    $kelPct = $aspekPsikologi['conscientiousness']['persen'] ?? 0;
    $depPct = $aspekPsikologi['neuroticism']['persen'] ?? 0;
    $prePct = $aspekPsikologi['extraversion']['persen'] ?? 0;

    // Nama user
    $namaUser = Auth::user()->name ?? 'Pengguna';
    $roleUser = Auth::user()->role ?? 'Mahasiswa';

    // Tanggal
    $tglDiagnosis = \Carbon\Carbon::parse($waktu)
        ->locale('id')
        ->isoFormat('D MMMM YYYY, HH:mm') . ' WIB';
@endphp

<style>
/* ===================== LAYOUT ===================== */
.breadcrumb-link {
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
    transition: all .2s ease;
}

.breadcrumb-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

.breadcrumb-separator {
    color: #94a3b8;
    margin: 0 4px;
}

.breadcrumb-current {
    color: #64748b;
}

.sbcare-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 20px 18px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
.sbcare-logo-icon {
    width: 38px; height: 38px;
    border-radius: 8px;
    background: #3b82f6;
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: bold; font-size: 14px;
}
.sbcare-logo span { color: white; font-size: 15px; font-weight: 700; }
.sbcare-logo span b { color: #60a5fa; }

.sbcare-nav { flex: 1; padding: 12px 0; }
.sbcare-nav a {
    display: flex; align-items: center; gap: 10px;
    padding: 11px 18px;
    color: rgba(255,255,255,0.6);
    text-decoration: none;
    font-size: 13.5px;
    border-left: 3px solid transparent;
    transition: all .2s;
}
.sbcare-nav a:hover, .sbcare-nav a.active {
    color: white;
    background: rgba(255,255,255,0.07);
    border-left-color: #60a5fa;
}
.sbcare-nav a svg { width: 18px; height: 18px; flex-shrink: 0; }

.sbcare-user {
    padding: 14px 18px;
    border-top: 1px solid rgba(255,255,255,0.1);
    display: flex; align-items: center; gap: 10px;
}
.sbcare-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #3b82f6;
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 700; font-size: 14px;
}
.sbcare-user-info { flex: 1; }
.sbcare-user-info .name { color: white; font-size: 13px; font-weight: 600; }
.sbcare-user-info .role { color: rgba(255,255,255,.5); font-size: 11px; }
.sbcare-logout {
    color: rgba(255,255,255,.4); font-size: 12px; padding: 6px 18px;
    display: flex; align-items: center; gap: 6px;
    text-decoration: none;
}
.sbcare-logout:hover { color: #f87171; }

/* ===================== HEADER ===================== */
.page-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 18px;
}
.page-header-bar .title-block h4 {
    font-size: 22px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.page-header-bar .title-block small {
    color: #94a3b8;
    font-size: 12px;
}
.page-actions { display: flex; gap: 8px; }
.btn-action {
    display: flex; align-items: center; gap: 6px;
    padding: 7px 14px;
    border-radius: 7px;
    font-size: 12.5px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all .15s;
}
.btn-history   { background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; }
.btn-pdf       { background: #16a34a; color: white; }
.btn-ulang     { background: #1d4ed8; color: white; }
.btn-action:hover { opacity: .85; }

/* ===================== SUMMARY CARD ===================== */
.summary-card {
    border-radius: 14px;
    background: linear-gradient(135deg, #1e3a5f 0%, #274e7a 100%);
    padding: 22px 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    box-shadow: 0 4px 18px rgba(30,58,95,.18);
}
.summary-left { display: flex; align-items: center; gap: 20px; }

/* Score circle */
.score-ring {
    position: relative;
    width: 90px; height: 90px;
}
.score-ring svg { transform: rotate(-90deg); }
.score-ring .ring-bg    { fill: none; stroke: rgba(255,255,255,.15); stroke-width: 6; }
.score-ring .ring-fill  { fill: none; stroke: #60a5fa; stroke-width: 6;
    stroke-linecap: round; transition: stroke-dashoffset .6s ease; }
.score-ring .center-text {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
}
.score-ring .center-text .num  { font-size: 22px; font-weight: 800; color: white; line-height: 1; }
.score-ring .center-text .den  { font-size: 10px; color: rgba(255,255,255,.6); }

.summary-info {}
.summary-info .badge-risiko {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    margin-bottom: 6px;
}
.badge-risiko-tinggi { background: rgba(239,68,68,.2); color: #fca5a5; border: 1px solid rgba(239,68,68,.3); }
.badge-risiko-sedang { background: rgba(234,179,8,.2); color: #fde68a; border: 1px solid rgba(234,179,8,.3); }
.badge-risiko-rendah { background: rgba(34,197,94,.2); color: #86efac; border: 1px solid rgba(34,197,94,.3); }
.summary-info h5 { color: white; font-weight: 700; font-size: 16px; margin: 0 0 3px; }
.summary-info .diag-date { color: rgba(255,255,255,.5); font-size: 11px; }

.summary-right { text-align: right; }
.summary-right .dim-row {
    display: flex; justify-content: flex-end; align-items: center;
    gap: 8px; margin-bottom: 4px; font-size: 12.5px;
}
.summary-right .dim-label { color: rgba(255,255,255,.6); }
.summary-right .dim-val   { color: white; font-weight: 600; min-width: 32px; text-align: right; }
.summary-right .dim-dot   { width: 8px; height: 8px; border-radius: 50%; }
.summary-right .user-line { color: rgba(255,255,255,.45); font-size: 11px; margin-top: 6px; }

/* ===================== GRID CARDS ===================== */
.diag-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
.diag-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 6px rgba(0,0,0,.06);
}
.diag-card-title {
    display: flex; align-items: center; gap: 7px;
    font-size: 13px; font-weight: 700; color: #1e293b;
    margin-bottom: 16px;
}
.diag-card-title svg { width: 16px; height: 16px; color: #3b82f6; }

/* Chart */
.chart-wrap {
    position: relative;
    width: 260px; height: 260px;
    margin: 0 auto 12px;
}
#chartBurnout { width: 100% !important; height: 100% !important; }
.chart-legend {
    display: flex; justify-content: center; gap: 16px;
    flex-wrap: wrap; font-size: 11px; color: #64748b;
}
.chart-legend span { display: flex; align-items: center; gap: 4px; }
.chart-legend .dot { width: 8px; height: 8px; border-radius: 50%; }

/* Variabel list */
.var-list { display: flex; flex-direction: column; gap: 8px; }
.var-item {
    display: flex; align-items: center; gap: 8px;
}
.var-badge {
    font-size: 9px; font-weight: 700; padding: 2px 5px;
    border-radius: 4px; min-width: 28px; text-align: center;
}
.badge-kel { background: #fee2e2; color: #dc2626; }
.badge-dep { background: #ffedd5; color: #ea580c; }
.badge-pre { background: #fef9c3; color: #ca8a04; }
.var-label { flex: 1; font-size: 12px; color: #374151; }
.var-bar-wrap { width: 100px; }
.var-bar-track { height: 5px; border-radius: 3px; background: #f1f5f9; overflow: hidden; }
.var-bar-fill  { height: 100%; border-radius: 3px; transition: width .4s; }
.var-score { font-size: 11.5px; color: #64748b; min-width: 26px; text-align: right; }
.var-dot-status { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

/* ===================== LOGIKA PAKAR ===================== */
.logika-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 6px rgba(0,0,0,.06);
    margin-bottom: 16px;
}
.logika-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 14px;
}
.logika-title { font-size: 13px; font-weight: 700; color: #1e293b;
    display: flex; align-items: center; gap: 7px; }
.cf-badge {
    background: #eff6ff; color: #2563eb;
    border: 1px solid #bfdbfe;
    border-radius: 6px; padding: 3px 10px;
    font-size: 11px; font-weight: 600;
}
.penelusuran-title { font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 10px;
    display: flex; align-items: center; gap: 6px; }
.penelusuran-title::before { content: '●'; color: #3b82f6; font-size: 8px; }

.fallback-box {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 10px;
    padding: 28px;
    text-align: center;
}
.fallback-box svg { width: 36px; height: 36px; color: #94a3b8; margin-bottom: 8px; }
.fallback-box p { color: #64748b; font-size: 12.5px; font-weight: 600; margin: 0 0 4px; }
.fallback-box small { color: #94a3b8; font-size: 11px; }

.cf-note {
    margin-top: 12px;
    background: #eff6ff;
    border-radius: 8px;
    padding: 10px 14px;
    display: flex; align-items: flex-start; gap: 8px;
    font-size: 11.5px; color: #3b82f6;
}
.cf-note svg { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; }

/* ===================== REKOMENDASI ===================== */
.rekom-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 6px rgba(0,0,0,.06);
    margin-bottom: 16px;
}
.rekom-title { font-size: 13px; font-weight: 700; color: #1e293b;
    display: flex; align-items: center; gap: 7px; margin-bottom: 14px; }
.rekom-alert {
    border-radius: 10px;
    padding: 16px 18px;
    border: 1px solid;
}
.rekom-alert.tinggi { background: #fffbeb; border-color: #fde68a; }
.rekom-alert.sedang { background: #fef3c7; border-color: #fcd34d; }
.rekom-alert.rendah { background: #f0fdf4; border-color: #bbf7d0; }
.rekom-alert .saran-label { font-size: 11px; font-weight: 700; color: #92400e; margin-bottom: 6px;
    display: flex; align-items: center; gap: 5px; }
.rekom-alert .saran-label.rendah { color: #166534; }
.rekom-alert p { font-size: 12.5px; color: #78350f; margin: 0; line-height: 1.6; }
.rekom-alert p.rendah { color: #166534; }

.periodic-note {
    display: flex; align-items: center; gap: 8px;
    font-size: 11.5px; color: #64748b;
    margin-top: 10px;
}
.periodic-note svg { width: 14px; height: 14px; color: #3b82f6; flex-shrink: 0; }

@media (max-width: 768px) {
    .sbcare-sidebar { display: none; }
    .sbcare-main { margin-left: 0; padding: 16px; }
    .diag-grid { grid-template-columns: 1fr; }
    .summary-card { flex-direction: column; align-items: flex-start; gap: 14px; }
    .summary-right { text-align: left; }
}

@media print {
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    /* Sembunyikan semua elemen layar */
    body * {
        visibility: hidden;
    }

    /* Sembunyikan dashboard asli, tampilkan hanya print-pdf */
    #print-area {
        display: none !important;
    }

    #print-pdf {
        display: block !important;
        visibility: visible !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    #print-pdf * {
        visibility: visible !important;
    }

    .page-actions {
        display: none !important;
    }

    body {
        background: white !important;
    }
}

@page {
    margin: 15px;
    size: A4 portrait;
}

/* ===================== EXPLAINABLE AI (XAI) ===================== */
.xai-toggle-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 12px;
    transition: all 0.2s;
}
.xai-toggle-btn:hover {
    background: #dbeafe;
}
.xai-toggle-btn svg {
    transition: transform 0.2s ease;
}
.xai-details {
    display: none;
    margin-top: 14px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    border-top: 2px solid #3b82f6;
}
.xai-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
    color: #334155;
    margin-bottom: 6px;
}
.xai-table th {
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    padding: 8px 10px;
    text-align: left;
    font-weight: 700;
}
.xai-table td {
    border: 1px solid #cbd5e1;
    padding: 8px 10px;
    vertical-align: top;
    background: white;
}
</style>

<div class="sbcare-wrapper">
    <!-- ============ MAIN ============ -->
    <main class="sbcare-main">

        <div id="print-area">
            <!-- Page header -->
            <div class="page-header-bar">
                <div class="title-block">
                    <h4>Hasil Diagnosis Burnout</h4>
                    <small>
                        <a href="{{ route('dashboard') }}" class="breadcrumb-link">
                            Dashboard
                        </a>

                        <span class="breadcrumb-separator">/</span>

                        <span class="breadcrumb-current">
                            Hasil Diagnosis
                        </span>
                    </small>
                </div>
                <div class="page-actions">
                    <a href="{{ route('history.index') }}" class="btn-action btn-history">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="13" height="13">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Riwayat
                    </a>
                    <a href="#" onclick="printHalaman()" class="btn-action btn-pdf">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="13" height="13">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak PDF
                    </a>
                    <a href="{{ route('diagnosis.form') }}" class="btn-action btn-ulang">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="13" height="13">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Diagnosis Ulang
                    </a>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="summary-card">
                <div class="summary-left">
                    <!-- Score ring -->
                    <div class="score-ring">
                        @php
                            $r   = 38; $cx = 45; $cy = 45;
                            $circ = 2 * pi() * $r;
                            $offset = $circ - ($cfPct / 100) * $circ;
                        @endphp
                        <svg viewBox="0 0 90 90" width="90" height="90">
                            <circle class="ring-bg"   cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"/>
                            <circle class="ring-fill"  cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                                stroke-dasharray="{{ $circ }}"
                                stroke-dashoffset="{{ $offset }}"/>
                        </svg>
                        <div class="center-text">
                            <span class="num">{{ $cfPct }}</span>
                            <span class="den">/100</span>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="summary-info">
                        <div class="badge-risiko {{ $risikoClass }}">
                            ● {{ $risikoLabel }} {{ $cfPct }}.0%
                        </div>
                        <h5>Hasil Diagnosis Burnout</h5>
                        <div class="diag-date">Diagnosis pada {{ $tglDiagnosis }}</div>
                    </div>
                </div>

                <div class="summary-right">
                    @foreach($aspekPsikologi as $aspek)
                    <div class="dim-row">
                        <span class="dim-label">{{ $aspek['label'] }}</span>
                        <div class="dim-dot" style="background:{{ $aspek['warna'] }}"></div>
                        <span class="dim-val">{{ $aspek['persen'] }}%</span>
                    </div>
                    @endforeach
                    <div class="user-line">Pengguna &nbsp;{{ $namaUser }}</div>
                </div>
            </div>

            <!-- Grid: Chart + Variabel -->
            <div class="diag-grid">

                <!-- Radar Chart -->
                <div class="diag-card">
                    <div class="diag-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Analisis Kepribadian OCEAN
                    </div>
                    <div class="chart-wrap">
                        <canvas id="chartBurnout"></canvas>
                    </div>
                    <div class="chart-legend">
                        <span><div class="dot" style="background:#ef4444"></div> Skor Anda</span>
                        <span><div class="dot" style="background:#94a3b8; opacity:.5"></div> Area Tidak Sehat (kritis)</span>
                    </div>
                </div>

                <!-- Detail Variabel -->
                <div class="diag-card">
                    <div class="diag-card-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Detail Skor per Variabel
                    </div>
                    <div class="var-list">
                        @foreach($namaVariabel as $i => $v)
                        @php
                            $skor = $jawaban[$i] ?? 0;
                            $pct  = $skor * 20;
                            $c    = $dimColor[$v['dim']];
                            $statusColor = $skor >= 4 ? '#ef4444' : ($skor >= 3 ? '#f97316' : '#22c55e');
                        @endphp
                        <div class="var-item">
                            <span class="var-badge {{ $c['badge'] }}">{{ $v['dim'] }}</span>
                            <span class="var-label">{{ $v['label'] }}</span>
                            <div class="var-bar-wrap">
                                <div class="var-bar-track">
                                    <div class="var-bar-fill" style="width:{{ $pct }}%; background:{{ $c['bg'] }}"></div>
                                </div>
                            </div>
                            <span class="var-score">{{ $skor }}/5</span>
                            <div class="var-dot-status" style="background:{{ $statusColor }}"></div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Logika Pakar -->
            <div class="logika-card">
                <div class="logika-header">
                    <div class="logika-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16" color="#3b82f6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Logika Pakar
                    </div>
                    <span class="cf-badge">Skor EDAS: {{ $edasScore !== null ? number_format($edasScore, 2) . '%' : number_format($total, 1) . '%' }}</span>
                </div>

                <div class="penelusuran-title">Perhitungan SPK Metode EDAS</div>

                <div class="fallback-box">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p>Alternatif terbaik: {{ $risikoLabel }}</p>
                    <small>EDAS meranking alternatif Risiko Rendah, Sedang, dan Tinggi berdasarkan jarak trait OCEAN dari solusi rata-rata.</small>
                </div>

                @if($edas && isset($edas['ranking']))
                <div class="var-list" style="margin-top:12px;">
                    @foreach($edas['ranking'] as $kode => $rank)
                    <div class="var-item">
                        <span class="var-badge {{ $kode === 'tinggi' ? 'badge-kel' : ($kode === 'sedang' ? 'badge-pre' : 'badge-dep') }}">{{ strtoupper(substr($kode, 0, 1)) }}</span>
                        <span class="var-label">{{ $rank['label'] }}</span>
                        <div class="var-bar-wrap">
                            <div class="var-bar-track">
                                <div class="var-bar-fill" style="width:{{ $rank['skor'] }}%; background:#3b82f6"></div>
                            </div>
                        </div>
                        <span class="var-score">{{ number_format($rank['skor'], 2) }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <button class="xai-toggle-btn" onclick="toggleXaiDetails()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="13" height="13" id="xai-toggle-icon" style="transform: rotate(0deg);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    Tampilkan Detail Transparansi Matematis (Explainable AI)
                </button>

                <div class="xai-details" id="xai-details-content">
                    <div style="font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Matriks Evaluasi & Kecocokan Trait (Similarity Matrix)</div>
                    <p style="font-size: 11px; color: #64748b; margin-bottom: 12px; line-height: 1.5;">
                        Tabel di bawah ini menunjukkan tingkat kemiripan/kecocokan antara skor kepribadian Anda dengan target profil pakar untuk setiap alternatif risiko (dihitung menggunakan formula $100 - |\text{Skor Anda} - \text{Target Pakar}|$). Solusi Rata-rata (AV) dihitung sebagai rata-rata tingkat kemiripan dari ketiga alternatif untuk mengukur tingkat kejauhan keputusan (PDA/NDA) dalam metode EDAS.
                    </p>

                    <div style="overflow-x: auto;">
                        <table class="xai-table">
                            <thead>
                                <tr>
                                    <th>Kriteria (Trait)</th>
                                    <th>Bobot</th>
                                    <th>Skor Anda</th>
                                    <th>Target Rendah</th>
                                    <th>Target Sedang</th>
                                    <th>Target Tinggi</th>
                                    <th>Rata-rata (AV)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $pakarTargets = [
                                        'openness' => ['rendah' => 65, 'sedang' => 55, 'tinggi' => 40],
                                        'conscientiousness' => ['rendah' => 80, 'sedang' => 55, 'tinggi' => 25],
                                        'extraversion' => ['rendah' => 65, 'sedang' => 50, 'tinggi' => 35],
                                        'agreeableness' => ['rendah' => 70, 'sedang' => 55, 'tinggi' => 35],
                                        'neuroticism' => ['rendah' => 20, 'sedang' => 55, 'tinggi' => 85],
                                    ];
                                @endphp
                                @foreach($aspekPsikologi as $key => $aspek)
                                <tr>
                                    <td style="font-weight: 700; background: #f8fafc;">
                                        <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:{{ $aspek['warna'] }}; margin-right:4px;"></span>
                                        {{ $aspek['label'] }} ({{ $aspek['kode'] }})
                                    </td>
                                    <td style="text-align: center; font-weight: 600;">{{ number_format($aspek['bobot'] * 100) }}%</td>
                                    <td style="font-weight: 700; color: #2563eb; text-align: center; background: #eff6ff;">{{ $aspek['persen'] }}%</td>
                                    
                                    {{-- Rendah --}}
                                    <td>
                                        <div style="font-weight: 600; color: #475569;">Target: {{ $pakarTargets[$key]['rendah'] }}%</div>
                                        <div style="color: #16a34a; font-weight: 700; margin-top: 2px;">Cocok: {{ number_format($edas['matrix']['rendah'][$key], 1) }}%</div>
                                    </td>

                                    {{-- Sedang --}}
                                    <td>
                                        <div style="font-weight: 600; color: #475569;">Target: {{ $pakarTargets[$key]['sedang'] }}%</div>
                                        <div style="color: #d97706; font-weight: 700; margin-top: 2px;">Cocok: {{ number_format($edas['matrix']['sedang'][$key], 1) }}%</div>
                                    </td>

                                    {{-- Tinggi --}}
                                    <td>
                                        <div style="font-weight: 600; color: #475569;">Target: {{ $pakarTargets[$key]['tinggi'] }}%</div>
                                        <div style="color: #dc2626; font-weight: 700; margin-top: 2px;">Cocok: {{ number_format($edas['matrix']['tinggi'][$key], 1) }}%</div>
                                    </td>

                                    {{-- Average Solution --}}
                                    <td style="font-weight: 700; background: #f8fafc; text-align: center; color: #0f172a;">
                                        {{ number_format($edas['average_solution'][$key], 2) }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <script>
                    function toggleXaiDetails() {
                        var content = document.getElementById('xai-details-content');
                        var icon = document.getElementById('xai-toggle-icon');
                        if (content.style.display === 'block') {
                            content.style.display = 'none';
                            icon.style.transform = 'rotate(0deg)';
                        } else {
                            content.style.display = 'block';
                            icon.style.transform = 'rotate(180deg)';
                        }
                    }
                </script>

                <div class="cf-note">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Nilai kriteria berasal dari model OCEAN/Big Five, lalu model EDAS memilih alternatif keputusan dengan appraisal score tertinggi.
                </div>
            </div>

            <!-- Rekomendasi -->
            <div class="rekom-card">
                <div class="rekom-title">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16" color="#f59e0b">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    Rekomendasi Pakar dan Tindakan
                </div>

                @if(!empty($aiRekomendasi))
                <div class="rekom-alert" style="background:#f0f9ff; border-color:#38bdf8;">
                    <div class="saran-label" style="color:#0ea5e9;">🤖 Rekomendasi AI Gemini:</div>
                    <p style="color:#0e7490;">{!! nl2br(e($aiRekomendasi)) !!}</p>
                </div>
                @else
                    @if($total >= 70)
                    <div class="rekom-alert tinggi">
                        <div class="saran-label">💡 Saran Utama:</div>
                        <p>Kondisi Anda menunjukkan tingkat burnout yang tinggi. Disarankan untuk segera melakukan penyesuaian pola aktivitas dan mencari dukungan profesional agar kondisi tidak semakin memburuk.</p>
                    </div>
                    @elseif($total >= 40)
                    <div class="rekom-alert sedang">
                        <div class="saran-label">💡 Saran Utama:</div>
                        <p>Tingkat burnout Anda berada di level sedang. Perlu manajemen waktu yang lebih baik dan istirahat yang cukup untuk mencegah kondisi memburuk.</p>
                    </div>
                    @else
                    <div class="rekom-alert rendah">
                        <div class="saran-label rendah">✅ Saran Utama:</div>
                        <p class="rendah">Kondisi Anda masih baik. Tetap jaga keseimbangan aktivitas dan istirahat yang cukup untuk mempertahankan kondisi ini.</p>
                    </div>
                    @endif
                @endif

                <div class="periodic-note">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Lakukan diagnosis secara berkala untuk memantau perkembangan kondisi Anda.
                </div>
            </div>
        </div>
        {{-- ============ TEMPLATE KHUSUS PDF (mirip gambar) ============ --}}
        <div id="print-pdf" style="display:none; font-family: Arial, sans-serif; font-size: 12px; color: #1e293b; padding: 10px;">

            {{-- HEADER IDENTITAS --}}
            <table style="width:100%; border-collapse:collapse; margin-bottom:16px; border:1px solid #cbd5e1; border-radius:8px; overflow:hidden;">
                <thead>
                    <tr style="background:#1e3a5f;">
                        <td colspan="4" style="padding:10px 14px; color:white; font-size:13px; font-weight:700; letter-spacing:0.5px;">
                            IDENTITAS MAHASISWA
                        </td>
                    </tr>
                </thead>
                <tbody style="background:#f8fafc;">
                    <tr>
                        <td style="padding:7px 14px; width:22%; color:#64748b; font-size:11px;">Nama Lengkap</td>
                        <td style="padding:7px 14px; width:28%; font-weight:600;">: {{ Auth::user()->name ?? '-' }}</td>
                        <td style="padding:7px 14px; width:22%; color:#64748b; font-size:11px;">ID Diagnosis</td>
                        <td style="padding:7px 14px; width:28%; font-weight:600;">: #{{ $diagnosisId ?? '1' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:7px 14px; color:#64748b; font-size:11px;">Nomor Induk (NIM)</td>
                        <td style="padding:7px 14px; font-weight:600;">: {{ Auth::user()->nim ?? '-' }}</td>
                        <td style="padding:7px 14px; color:#64748b; font-size:11px;">Tgl Diagnosis</td>
                        <td style="padding:7px 14px; font-weight:600;">: {{ \Carbon\Carbon::parse($waktu)->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:7px 14px; color:#64748b; font-size:11px;">Program Studi</td>
                        <td style="padding:7px 14px; font-weight:600; color:#1d4ed8;">: {{ Auth::user()->jurusan ?? '-' }}</td>
                        <td style="padding:7px 14px; color:#64748b; font-size:11px;">Waktu Selesai</td>
                        <td style="padding:7px 14px; font-weight:600;">: {{ \Carbon\Carbon::parse($waktu)->format('H.i') }} WIB</td>
                    </tr>
                    <tr>
                        <td style="padding:7px 14px; color:#64748b; font-size:11px;">Angkatan</td>
                        <td style="padding:7px 14px; font-weight:600;">: {{ Auth::user()->angkatan ?? '-' }}</td>
                        <td style="padding:7px 14px; color:#64748b; font-size:11px;">Metode Analisis</td>
                        <td style="padding:7px 14px; font-weight:600;">: EDAS</td>
                    </tr>
                </tbody>
            </table>

            {{-- HASIL DIAGNOSIS --}}
            <div style="font-size:13px; font-weight:700; color:#1e293b; margin-bottom:10px; padding-bottom:4px; border-bottom:2px solid #e2e8f0;">
                Hasil Diagnosis
            </div>

            <table style="width:100%; border-collapse:collapse; margin-bottom:16px;">
                <tr>
                    {{-- CF Box --}}
                    <td style="width:30%; vertical-align:middle; padding-right:16px;">
                        <div style="background:{{ $total >= 70 ? '#fef2f2' : ($total >= 40 ? '#fffbeb' : '#f0fdf4') }}; border:1px solid {{ $total >= 70 ? '#fecaca' : ($total >= 40 ? '#fde68a' : '#bbf7d0') }}; border-radius:10px; padding:18px; text-align:center;">
                            <div style="font-size:32px; font-weight:800; color:{{ $total >= 70 ? '#ef4444' : ($total >= 40 ? '#f59e0b' : '#22c55e') }}; line-height:1;">
                                {{ $total }}.0%
                            </div>
                            <div style="font-size:11px; color:#64748b; margin-top:4px;">Indeks Risiko Psikologis</div>
                            <div style="margin-top:8px; font-size:12px; font-weight:800; color:{{ $total >= 70 ? '#ef4444' : ($total >= 40 ? '#f59e0b' : '#22c55e') }};">
                                {{ $risikoLabel }}
                            </div>
                        </div>
                    </td>

                    {{-- Skor Dimensi --}}
                    <td style="vertical-align:top;">
                        <div style="font-size:12px; font-weight:700; color:#1e293b; margin-bottom:10px;">Skor Trait OCEAN</div>

                        @foreach($aspekPsikologi as $aspek)
                        <div style="margin-bottom:8px;">
                            <div style="font-size:11px; color:#64748b; margin-bottom:3px;">{{ $aspek['label'] }}</div>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="flex:1; height:10px; background:#f1f5f9; border-radius:5px; overflow:hidden;">
                                    <div style="width:{{ $aspek['persen'] }}%; height:100%; background:{{ $aspek['warna'] }}; border-radius:5px;"></div>
                                </div>
                                <span style="font-size:11px; font-weight:600; min-width:30px;">{{ $aspek['persen'] }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </td>
                </tr>
            </table>

            {{-- REKOMENDASI PAKAR --}}
            <div style="font-size:13px; font-weight:700; color:#1e293b; margin-bottom:10px; padding-bottom:4px; border-bottom:2px solid #e2e8f0;">
                Rekomendasi Pakar
            </div>
            <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:14px 16px; margin-bottom:16px;">
                <div style="font-size:11px; font-weight:700; color:#92400e; margin-bottom:6px;">💡 Saran Utama:</div>
                <p style="font-size:12px; color:#78350f; margin:0; line-height:1.6;">
                    @if($total >= 70)
                        Kondisi Anda menunjukkan tingkat burnout yang tinggi. Disarankan untuk segera melakukan penyesuaian pola aktivitas dan mencari dukungan profesional agar kondisi tidak semakin memburuk.
                    @elseif($total >= 40)
                        Tingkat burnout Anda berada di level sedang. Perlu manajemen waktu yang lebih baik dan istirahat yang cukup untuk mencegah kondisi memburuk.
                    @else
                        Kondisi Anda masih baik. Tetap jaga keseimbangan aktivitas dan istirahat yang cukup untuk mempertahankan kondisi ini.
                    @endif
                </p>
            </div>

            {{-- LOGIKA DIAGNOSIS --}}
            <div style="font-size:13px; font-weight:700; color:#1e293b; margin-bottom:10px; padding-bottom:4px; border-bottom:2px solid #e2e8f0;">
                Logika Diagnosis EDAS
            </div>
            <div style="background:#f8fafc; border:1px dashed #cbd5e1; border-radius:8px; padding:20px; text-align:center; margin-bottom:16px;">
                <div style="font-size:12px; font-weight:600; color:#64748b;">Alternatif terbaik: {{ $risikoLabel }}</div>
                <div style="font-size:11px; color:#94a3b8; margin-top:4px;">Skor EDAS: {{ $edasScore !== null ? number_format($edasScore, 2) . '%' : '-' }}. Alternatif dengan appraisal score tertinggi dipilih sebagai hasil keputusan.</div>
            </div>

            {{-- TABEL DETAIL --}}
            <div style="font-size:13px; font-weight:700; color:#1e293b; margin-bottom:10px; padding-bottom:4px; border-bottom:2px solid #e2e8f0;">
                Detail Jawaban &amp; Skor Variabel
            </div>
            <table style="width:100%; border-collapse:collapse; margin-bottom:16px; font-size:11px;">
                <thead>
                    <tr style="background:#f1f5f9;">
                        <th style="padding:8px 10px; text-align:left; border:1px solid #e2e8f0; width:4%;">No</th>
                        <th style="padding:8px 10px; text-align:left; border:1px solid #e2e8f0; width:28%;">Variabel</th>
                        <th style="padding:8px 10px; text-align:left; border:1px solid #e2e8f0; width:18%;">Dimensi</th>
                        <th style="padding:8px 10px; text-align:center; border:1px solid #e2e8f0; width:8%;">Nilai</th>
                        <th style="padding:8px 10px; text-align:left; border:1px solid #e2e8f0;">Keterangan</th>
                        <th style="padding:8px 10px; text-align:center; border:1px solid #e2e8f0; width:14%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $keteranganMap = [
                        1 => ['Sangat Tidak Setuju','Tidak sesuai'],
                        2 => ['Tidak Setuju','Kurang sesuai'],
                        3 => ['Netral','Cukup sesuai'],
                        4 => ['Setuju','Sesuai'],
                        5 => ['Sangat Setuju','Sangat sesuai'],
                    ];
                    $dimLabel = [
                        'O' => 'Openness',
                        'C' => 'Conscientiousness',
                        'E' => 'Extraversion',
                        'A' => 'Agreeableness',
                        'N' => 'Neuroticism',
                    ];
                    $dimColorText = [
                        'O' => '#0ea5e9',
                        'C' => '#ef4444',
                        'E' => '#eab308',
                        'A' => '#22c55e',
                        'N' => '#a855f7',
                    ];
                    @endphp

                    @foreach($namaVariabel as $i => $v)
                    @php
                        $skor = $jawaban[$i] ?? 0;
                        $ket  = $keteranganMap[$skor] ?? ['–','–'];
                        $statusLabel = $skor >= 4 ? 'Perlu Perhatian' : ($skor >= 3 ? 'Waspada' : 'Baik');
                        $statusBg    = $skor >= 4 ? '#fef2f2' : ($skor >= 3 ? '#fffbeb' : '#f0fdf4');
                        $statusColor = $skor >= 4 ? '#ef4444' : ($skor >= 3 ? '#f59e0b' : '#22c55e');
                        $rowBg       = $i % 2 === 0 ? '#ffffff' : '#f8fafc';
                    @endphp
                    <tr style="background:{{ $rowBg }};">
                        <td style="padding:7px 10px; border:1px solid #e2e8f0; text-align:center;">{{ $i + 1 }}</td>
                        <td style="padding:7px 10px; border:1px solid #e2e8f0;">{{ $v['label'] }}</td>
                        <td style="padding:7px 10px; border:1px solid #e2e8f0; color:{{ $dimColorText[$v['dim']] }}; font-weight:600;">
                            {{ $dimLabel[$v['dim']] }}
                        </td>
                        <td style="padding:7px 10px; border:1px solid #e2e8f0; text-align:center; font-weight:700;">{{ $skor }}/5</td>
                        <td style="padding:7px 10px; border:1px solid #e2e8f0; color:#64748b;">{{ $ket[0] }} – {{ $ket[1] }}</td>
                        <td style="padding:7px 10px; border:1px solid #e2e8f0; text-align:center;">
                            <span style="background:{{ $statusBg }}; color:{{ $statusColor }}; padding:2px 8px; border-radius:10px; font-size:10px; font-weight:700; white-space:nowrap;">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- PERINGATAN BAWAH --}}
            @if($total >= 70)
            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:14px 16px; margin-bottom:16px;">
                <div style="font-size:12px; font-weight:700; color:#dc2626; margin-bottom:6px;">⚠ Peringatan: Risiko Burnout Tinggi</div>
                <p style="font-size:11.5px; color:#7f1d1d; margin:0; line-height:1.6;">
                    Hasil diagnosis menunjukkan Anda berada dalam kondisi burnout yang tinggi. Sangat disarankan untuk segera berkonsultasi dengan <strong>konselor atau psikolog kampus</strong>. Jangan ragu untuk meminta bantuan — ini adalah tanda kekuatan. Bukan kelemahan.
                </p>
            </div>
            @endif

            {{-- FOOTER --}}
            <div style="text-align:center; font-size:10px; color:#94a3b8; border-top:1px solid #e2e8f0; padding-top:10px; margin-top:8px;">
                BurnoutCheck — Sistem Pakar Burnout Mahasiswa. Dokumen ini bersifat rahasia. Dicetak otomatis oleh sistem. {{ now()->format('d/m/Y') }}
            </div>

        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const aspekLabels = @json(array_column($aspekPsikologi, 'label'));
    const aspekScores = @json(array_column($aspekPsikologi, 'persen'));

    new Chart(document.getElementById('chartBurnout'), {
        type: 'radar',
        data: {
            labels: aspekLabels,
            datasets: [
                {
                    label: 'Skor Anda',
                    data: aspekScores,
                    fill: true,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,0.25)',
                    pointBackgroundColor: '#ef4444',
                    pointRadius: 4,
                    borderWidth: 2,
                },
                {
                    label: 'Area Risiko Tinggi',
                    data: [70, 70, 70, 70, 70],
                    fill: true,
                    borderColor: 'rgba(148,163,184,0.4)',
                    backgroundColor: 'rgba(148,163,184,0.08)',
                    pointRadius: 0,
                    borderWidth: 1,
                    borderDash: [4, 4],
                }
            ]
        },
        options: {
            responsive: true,
            layout: {
                padding: 20
            },
            plugins: { legend: { display: false } },
            scales: {
                r: {
                    suggestedMin: 0,
                    suggestedMax: 100,
                    ticks: { stepSize: 20, font: { size: 9 }, color: '#94a3b8' },
                    grid:  { color: 'rgba(0,0,0,0.06)' },
                    pointLabels: { font: { size: 10 }, color: '#64748b', padding: 15 }
                }
            }
        }
    });
});

function printHalaman() {
    // Convert canvas → img
    document.querySelectorAll('canvas').forEach(canvas => {
        const img = document.createElement('img');
        img.src = canvas.toDataURL('image/png');
        img.style.width  = canvas.offsetWidth + 'px';
        img.style.height = canvas.offsetHeight + 'px';
        img.classList.add('canvas-print-img');
        canvas.style.display = 'none';
        canvas.parentNode.insertBefore(img, canvas.nextSibling);
    });

    window.print();

    // Kembalikan canvas
    document.querySelectorAll('.canvas-print-img').forEach(img => img.remove());
    document.querySelectorAll('canvas').forEach(c => c.style.display = '');
}
</script>
@endsection
