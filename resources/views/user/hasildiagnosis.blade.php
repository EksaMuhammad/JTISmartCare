@extends('app')

@section('content')

@php
    $namaVariabel = [
        // EXTRAVERSION (4 items: 0-3)
        0  => ['label' => 'Aktif dalam diskusi teknis dan presentasi', 'dim' => 'E'],
        1  => ['label' => 'Lebih suka bekerja sendiri daripada kolaborasi', 'dim' => 'E', 'reverse' => true],
        2  => ['label' => 'Senang mengikuti komunitas tech dan hackathon', 'dim' => 'E'],
        3  => ['label' => 'Tidak nyaman menjadi leader dalam project', 'dim' => 'E', 'reverse' => true],
        
        // AGREEABLENESS (4 items: 4-7)
        4  => ['label' => 'Mudah membantu teman debugging dan penjelasan teknis', 'dim' => 'A'],
        5  => ['label' => 'Tidak peduli dengan masalah teman dalam project', 'dim' => 'A', 'reverse' => true],
        6  => ['label' => 'Berempati dan support saat teman struggle deadline', 'dim' => 'A'],
        7  => ['label' => 'Tidak tertarik dengan masalah teman sekelas', 'dim' => 'A', 'reverse' => true],
        
        // CONSCIENTIOUSNESS (4 items: 8-11)
        8  => ['label' => 'Menyelesaikan assignment tepat waktu dengan kualitas baik', 'dim' => 'C'],
        9  => ['label' => 'Sering menunda pekerjaan coding sampai last minute', 'dim' => 'C', 'reverse' => true],
        10 => ['label' => 'Terorganisir dalam mengelola project dan version control', 'dim' => 'C'],
        11 => ['label' => 'Berantakan dalam menyimpan dan mendokumentasikan files', 'dim' => 'C', 'reverse' => true],
        
        // NEUROTICISM (4 items: 12-15)
        12 => ['label' => 'Mudah cemas dengan deadline project dan exam teknis', 'dim' => 'N'],
        13 => ['label' => 'Tenang meski hadapi bug kompleks atau masalah teknis', 'dim' => 'N', 'reverse' => true],
        14 => ['label' => 'Khawatir jika nilai/performance tidak sesuai ekspektasi', 'dim' => 'N'],
        15 => ['label' => 'Jarang tertekan meski mengerjakan project kompleks', 'dim' => 'N', 'reverse' => true],
        
        // OPENNESS (4 items: 16-19)
        16 => ['label' => 'Tertarik belajar programming language dan teknologi baru', 'dim' => 'O'],
        17 => ['label' => 'Lebih suka gunakan bahasa/tools yang sudah familiar', 'dim' => 'O', 'reverse' => true],
        18 => ['label' => 'Punya imajinasi tinggi dalam design algorithm/architecture', 'dim' => 'O'],
        19 => ['label' => 'Cepat belajar konsep teknis yang kompleks', 'dim' => 'O'],
    ];

    $dimColor = [
        'O' => ['bg' => '#0ea5e9', 'text' => '#0ea5e9', 'badge' => 'badge-dep', 'label' => 'Openness'],
        'C' => ['bg' => '#ef4444', 'text' => '#ef4444', 'badge' => 'badge-kel', 'label' => 'Conscientiousness'],
        'E' => ['bg' => '#eab308', 'text' => '#eab308', 'badge' => 'badge-pre', 'label' => 'Extraversion'],
        'A' => ['bg' => '#22c55e', 'text' => '#22c55e', 'badge' => 'badge-kel', 'label' => 'Agreeableness'],
        'N' => ['bg' => '#a855f7', 'text' => '#a855f7', 'badge' => 'badge-dep', 'label' => 'Neuroticism'],
    ];

    $jawaban = $jawaban ?? array_fill(0, 20, 1);

    $aspekPsikologi = $aspekPsikologi ?? [
        'openness' => ['label' => 'Openness', 'items' => [16, 17, 18, 19], 'bobot' => 0.15, 'warna' => '#0ea5e9', 'kode' => 'O'],
        'conscientiousness' => ['label' => 'Conscientiousness', 'items' => [8, 9, 10, 11], 'bobot' => 0.25, 'warna' => '#ef4444', 'kode' => 'C'],
        'extraversion' => ['label' => 'Extraversion', 'items' => [0, 1, 2, 3], 'bobot' => 0.20, 'warna' => '#eab308', 'kode' => 'E'],
        'agreeableness' => ['label' => 'Agreeableness', 'items' => [4, 5, 6, 7], 'bobot' => 0.15, 'warna' => '#22c55e', 'kode' => 'A'],
        'neuroticism' => ['label' => 'Neuroticism', 'items' => [12, 13, 14, 15], 'bobot' => 0.25, 'warna' => '#a855f7', 'kode' => 'N'],
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
            $raw = 0;
            foreach ($data['items'] as $index) {
                $skor = $jawaban[$index] ?? 1;
                // Handle reverse scoring
                if (!empty($namaVariabel[$index]['reverse'])) {
                    $skor = 6 - $skor; // Reverse: 1->5, 2->4, 3->3, 4->2, 5->1
                }
                $raw += $skor;
            }
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

    // Gunakan rekomendasi yang dikirimkan dari Controller (sudah diproses & disimpan di DB)
    $aiRekomendasi = $aiRekomendasi ?? null;

    // Konversi markdown dari Gemini AI ke HTML sederhana
    $aiRekomendasiHtml = null;
    if (!empty($aiRekomendasi) && $aiRekomendasi !== '-') {
        $md = e($aiRekomendasi); // escape dulu untuk keamanan
        // Bold: **teks** → <strong>teks</strong>
        $md = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $md);
        // Italic: *teks* → <em>teks</em>
        $md = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $md);
        // Heading H1-H3
        $md = preg_replace('/^### (.+)$/m', '<h4 style="margin:12px 0 4px;color:#1e3a5f;font-size:13px;">$1</h4>', $md);
        $md = preg_replace('/^## (.+)$/m', '<h3 style="margin:14px 0 4px;color:#1e3a5f;font-size:14px;">$1</h3>', $md);
        $md = preg_replace('/^# (.+)$/m', '<h3 style="margin:14px 0 4px;color:#1e3a5f;font-size:15px;font-weight:700;">$1</h3>', $md);
        // Numbered list: "1. item"
        $md = preg_replace('/^\d+\. (.+)$/m', '<li style="margin-bottom:4px;">$1</li>', $md);
        // Bullet list: "- item" or "* item"
        $md = preg_replace('/^[\-\*] (.+)$/m', '<li style="margin-bottom:4px;">$1</li>', $md);
        // Wrap consecutive <li> in <ul>
        $md = preg_replace('/(<li[^>]*>.*?<\/li>(\s*<li[^>]*>.*?<\/li>)*)/s', '<ul style="padding-left:18px;margin:6px 0;">$1</ul>', $md);
        // Newlines → <br>
        $md = nl2br($md);
        // Remove double <br> after block elements
        $md = preg_replace('/(<\/h[1-6]>|<\/ul>|<\/li>)\s*(<br\s*\/?>)+/i', '$1', $md);
        $aiRekomendasiHtml = $md;
    }
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
    margin-bottom: 24px;
    gap: 16px;
}
.page-header-bar .title-block h4 {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 4px;
}
.page-header-bar .title-block small {
    color: #64748b;
    font-size: 12px;
}
.page-actions { display: flex; gap: 10px; }
.btn-action {
    display: flex; align-items: center; gap: 7px;
    padding: 9px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s;
    white-space: nowrap;
}
.btn-history   { background: #f8fafc; border: 2px solid #e2e8f0; color: #475569; }
.btn-history:hover { background: #f1f5f9; border-color: #cbd5e1; }
.btn-pdf       { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: white; box-shadow: 0 4px 12px rgba(22,163,74,.2); }
.btn-pdf:hover { box-shadow: 0 6px 16px rgba(22,163,74,.3); }
.btn-ulang     { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; box-shadow: 0 4px 12px rgba(37,99,235,.2); }
.btn-ulang:hover { box-shadow: 0 6px 16px rgba(37,99,235,.3); }

/* ===================== SUMMARY CARD ===================== */
.summary-card {
    border-radius: 16px;
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1e40af 100%);
    padding: 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    box-shadow: 0 8px 24px rgba(15,23,42,.2);
    border: 1px solid rgba(255,255,255,.1);
}
.summary-left { display: flex; align-items: center; gap: 24px; }

/* Score circle */
.score-ring {
    position: relative;
    width: 100px; height: 100px;
    flex-shrink: 0;
}
.score-ring svg { transform: rotate(-90deg); }
.score-ring .ring-bg    { fill: none; stroke: rgba(255,255,255,.1); stroke-width: 6; }
.score-ring .ring-fill  { fill: none; stroke: #60a5fa; stroke-width: 6;
    stroke-linecap: round; transition: stroke-dashoffset .8s ease; }
.score-ring .center-text {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
}
.score-ring .center-text .num  { font-size: 26px; font-weight: 800; color: white; line-height: 1; }
.score-ring .center-text .den  { font-size: 11px; color: rgba(255,255,255,.6); margin-top: 2px; }

.summary-info {}
.summary-info .badge-risiko {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 24px; font-size: 12px; font-weight: 700;
    margin-bottom: 8px;
}
.badge-risiko-tinggi { background: rgba(239,68,68,.25); color: #fca5a5; border: 1px solid rgba(239,68,68,.4); }
.badge-risiko-sedang { background: rgba(234,179,8,.25); color: #fde68a; border: 1px solid rgba(234,179,8,.4); }
.badge-risiko-rendah { background: rgba(34,197,94,.25); color: #86efac; border: 1px solid rgba(34,197,94,.4); }
.summary-info h5 { color: white; font-weight: 700; font-size: 18px; margin: 0 0 4px; }
.summary-info .diag-date { color: rgba(255,255,255,.5); font-size: 12px; }

.summary-right { text-align: right; }
.summary-right .dim-row {
    display: flex; justify-content: flex-end; align-items: center;
    gap: 10px; margin-bottom: 6px; font-size: 13px;
}
.summary-right .dim-label { color: rgba(255,255,255,.6); }
.summary-right .dim-val   { color: white; font-weight: 700; min-width: 36px; text-align: right; }
.summary-right .dim-dot   { width: 9px; height: 9px; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,.2); }
.summary-right .user-line { color: rgba(255,255,255,.45); font-size: 12px; margin-top: 8px; }

/* ===================== GRID CARDS ===================== */
.diag-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px; }
.diag-card {
    background: white;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    border: 1px solid #f1f5f9;
    transition: all 0.3s ease;
}
.diag-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
}
.diag-card-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 14px; font-weight: 700; color: #0f172a;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f1f5f9;
}
.diag-card-title svg { width: 18px; height: 18px; color: #3b82f6; flex-shrink: 0; }

/* Chart */
.chart-wrap {
    position: relative;
    width: 280px; height: 280px;
    margin: 0 auto 16px;
}
#chartBurnout { width: 100% !important; height: 100% !important; }
.chart-legend {
    display: flex; justify-content: center; gap: 18px;
    flex-wrap: wrap; font-size: 12px; color: #64748b; font-weight: 500;
}
.chart-legend span { display: flex; align-items: center; gap: 6px; }
.chart-legend .dot { width: 9px; height: 9px; border-radius: 50%; }

/* Variabel list - Accordion Style */
.var-list { display: flex; flex-direction: column; gap: 0; }

/* Dimension section header - Now clickable */
.var-dimension-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 14px;
    margin: 0;
    font-weight: 700;
    font-size: 13px;
    border-bottom: 2px solid;
    background: #fafbfc;
    cursor: pointer;
    user-select: none;
    transition: all 0.2s ease;
    border-radius: 8px;
    margin-bottom: 2px;
}
.var-dimension-header:hover {
    background: #f1f5f9;
}
.var-dimension-header.active {
    background: #f0f9ff;
}
.var-dimension-header::before {
    content: '▼';
    display: inline-block;
    width: 16px;
    text-align: center;
    transition: transform 0.3s ease;
    font-size: 10px;
}
.var-dimension-header:not(.active)::before {
    transform: rotate(-90deg);
}
.var-dimension-badge {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 700;
    min-width: 28px;
    text-align: center;
    flex-shrink: 0;
}

.var-items-container {
    display: none;
    flex-direction: column;
    gap: 0;
    margin-bottom: 8px;
}
.var-items-container.active {
    display: flex;
}

.var-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px;
    border-radius: 0;
    background: white;
    border-left: 3px solid transparent;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.15s ease;
}
.var-item:last-child {
    border-bottom: none;
}
.var-item:hover {
    background: #fafbfc;
}
.var-badge {
    font-size: 10px; font-weight: 700; padding: 2px 6px;
    border-radius: 5px; min-width: 24px; text-align: center;
    flex-shrink: 0;
}
.badge-kel { background: #fee2e2; color: #991b1b; }
.badge-dep { background: #ffedd5; color: #92400e; }
.badge-pre { background: #fef9c3; color: #713f12; }
.var-label { flex: 1; font-size: 12px; color: #334155; font-weight: 500; line-height: 1.4; }
.var-bar-wrap { width: 70px; flex-shrink: 0; }
.var-bar-track { height: 4px; border-radius: 2px; background: #e2e8f0; overflow: hidden; }
.var-bar-fill  { height: 100%; border-radius: 2px; transition: width .4s; }
.var-score { font-size: 12px; color: #475569; font-weight: 600; min-width: 28px; text-align: center; flex-shrink: 0; }
.var-dot-status { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

/* ===================== LOGIKA PAKAR ===================== */
.logika-card {
    background: white;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    border: 1px solid #f1f5f9;
    margin-bottom: 18px;
}
.logika-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f1f5f9;
}
.logika-title { font-size: 14px; font-weight: 700; color: #0f172a;
    display: flex; align-items: center; gap: 8px; }
.logika-title svg { width: 18px; height: 18px; }
.cf-badge {
    background: #dbeafe; color: #0c4a6e;
    border: 1px solid #bfdbfe;
    border-radius: 8px; padding: 5px 12px;
    font-size: 12px; font-weight: 700;
}
.penelusuran-title { font-size: 12.5px; font-weight: 700; color: #0f172a; margin-bottom: 10px;
    display: flex; align-items: center; gap: 6px; }
.penelusuran-title::before { content: '●'; color: #3b82f6; font-size: 8px; }

.fallback-box {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 2px solid #86efac;
    border-radius: 12px;
    padding: 32px 24px;
    text-align: center;
}
.fallback-box svg { width: 40px; height: 40px; color: #22c55e; margin-bottom: 10px; }
.fallback-box p { color: #16a34a; font-size: 13.5px; font-weight: 700; margin: 0 0 4px; }
.fallback-box small { color: #86efac; font-size: 12px; }

.cf-note {
    margin-top: 16px;
    background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
    border-radius: 10px;
    border: 1px solid #bfdbfe;
    padding: 12px 16px;
    display: flex; align-items: flex-start; gap: 10px;
    font-size: 12px; color: #0c4a6e; line-height: 1.5;
}
.cf-note svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px; }

/* ===================== CARA BACA HASIL ===================== */
.cara-baca-wrap {
    margin-top: 18px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}
.cara-baca-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    cursor: pointer;
    user-select: none;
    transition: background 0.2s;
}
.cara-baca-header:hover { background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); }
.cara-baca-header svg { width: 15px; height: 15px; color: #3b82f6; flex-shrink: 0; }
.cara-baca-header .caret {
    margin-left: auto;
    font-size: 10px;
    color: #94a3b8;
    transition: transform 0.3s;
}
.cara-baca-header.open .caret { transform: rotate(180deg); }
.cara-baca-body {
    display: none;
    padding: 14px 16px;
    background: white;
}
.cara-baca-body.open { display: block; }
.risk-guide-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-bottom: 14px;
}
.risk-guide-item {
    border-radius: 10px;
    padding: 12px 14px;
    border: 2px solid;
}
.risk-guide-item.rendah  { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border-color:#86efac; }
.risk-guide-item.sedang  { background: linear-gradient(135deg,#fffbeb,#fef3c7); border-color:#fde68a; }
.risk-guide-item.tinggi  { background: linear-gradient(135deg,#fef2f2,#fee2e2); border-color:#fca5a5; }
.risk-guide-item .rg-icon { font-size: 20px; margin-bottom: 6px; }
.risk-guide-item .rg-label {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 5px;
}
.risk-guide-item.rendah .rg-label { color: #15803d; }
.risk-guide-item.sedang .rg-label { color: #b45309; }
.risk-guide-item.tinggi .rg-label { color: #b91c1c; }
.risk-guide-item .rg-desc { font-size: 11px; line-height: 1.55; }
.risk-guide-item.rendah .rg-desc { color: #166534; }
.risk-guide-item.sedang .rg-desc { color: #78350f; }
.risk-guide-item.tinggi .rg-desc { color: #991b1b; }

/* Metodologi Badges */
.metode-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    padding-top: 10px;
    border-top: 1px solid #f1f5f9;
}
.metode-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    flex-shrink: 0;
}
.metode-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
}
.metode-badge.blue  { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
.metode-badge.blue:hover { background: #bfdbfe; }
.metode-badge.violet { background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }
.metode-badge.violet:hover { background: #ddd6fe; }
.metode-badge.teal  { background: #ccfbf1; color: #0f766e; border: 1px solid #99f6e4; }
.metode-badge.teal:hover { background: #99f6e4; }
@media (max-width: 600px) {
    .risk-guide-grid { grid-template-columns: 1fr; }
}

/* ===================== REKOMENDASI ===================== */
.rekom-card {
    background: white;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    border: 1px solid #f1f5f9;
    margin-bottom: 18px;
}
.rekom-title { font-size: 14px; font-weight: 700; color: #0f172a;
    display: flex; align-items: center; gap: 8px; margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f1f5f9;
}
.rekom-title svg { width: 18px; height: 18px; }
.rekom-alert {
    border-radius: 12px;
    padding: 18px 20px;
    border: 2px solid;
    margin-bottom: 14px;
}
.rekom-alert.tinggi { background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-color: #fca5a5; }
.rekom-alert.sedang { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-color: #fde68a; }
.rekom-alert.rendah { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-color: #bbf7d0; }
.rekom-alert .saran-label { font-size: 12px; font-weight: 700; color: #92400e; margin-bottom: 8px;
    display: flex; align-items: center; gap: 6px; }
.rekom-alert .saran-label.rendah { color: #166534; }
.rekom-alert p { font-size: 13.5px; color: #78350f; margin: 0; line-height: 1.6; font-weight: 500; }
.rekom-alert p.rendah { color: #166534; }

/* AI recommendation rendered markdown */
.ai-rekom-content h3 { font-size: 13.5px; font-weight: 700; color: #1e3a5f; margin: 14px 0 5px; }
.ai-rekom-content h4 { font-size: 12.5px; font-weight: 700; color: #1e3a5f; margin: 12px 0 4px; }
.ai-rekom-content ul { padding-left: 18px; margin: 6px 0 10px; }
.ai-rekom-content li { margin-bottom: 5px; color: #334155; font-size: 13px; line-height: 1.65; }
.ai-rekom-content strong { color: #0f172a; }

.periodic-note {
    display: flex; align-items: flex-start; gap: 10px;
    font-size: 12px; color: #64748b;
    margin-top: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 3px solid #3b82f6;
}
.periodic-note svg { width: 16px; height: 16px; color: #3b82f6; flex-shrink: 0; margin-top: 2px; }

@media (max-width: 992px) {
    .diag-grid { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
    .sbcare-sidebar { display: none; }
    .sbcare-main { margin-left: 0; padding: 14px; }
    .diag-grid { grid-template-columns: 1fr; }
    .summary-card { flex-direction: column; align-items: flex-start; gap: 16px; }
    .summary-right { text-align: left; }
    .page-actions { flex-direction: column; }
    .btn-action { width: 100%; justify-content: center; }
    .var-item { gap: 8px; flex-wrap: wrap; }
    .var-label { flex: 1 100%; }
    .diag-card { padding: 16px; }
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
    gap: 7px;
    background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
    border: 2px solid #bfdbfe;
    color: #0c4a6e;
    padding: 9px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 14px;
    transition: all 0.2s;
}
.xai-toggle-btn:hover {
    background: linear-gradient(135deg, #bfdbfe 0%, #dbeafe 100%);
    border-color: #60a5fa;
}
.xai-toggle-btn svg {
    transition: transform 0.3s ease;
}
.xai-details {
    display: none;
    margin-top: 16px;
    padding: 18px;
    background: linear-gradient(135deg, #f0f9ff 0%, #f8fafc 100%);
    border: 2px solid #e0f2fe;
    border-radius: 12px;
    border-top: 3px solid #0284c7;
}
.xai-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
    color: #334155;
    margin-bottom: 8px;
}
.xai-table th {
    background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
    border: 1px solid #bae6fd;
    padding: 10px;
    text-align: left;
    font-weight: 700;
    color: #0c4a6e;
}
.xai-table td {
    border: 1px solid #e0f2fe;
    padding: 10px;
    vertical-align: top;
    background: white;
}
.xai-table tbody tr:nth-child(even) td {
    background: #f8fafc;
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
                        Skor per Item IPIP-20
                    </div>
                    <div class="var-list">
                        @php
                            $dimensionOrder = ['E', 'A', 'C', 'N', 'O'];
                            $dimensionLabels = [
                                'E' => 'Extraversion',
                                'A' => 'Agreeableness',
                                'C' => 'Conscientiousness',
                                'N' => 'Neuroticism',
                                'O' => 'Openness'
                            ];
                            $dimensionColors = [
                                'E' => '#eab308',
                                'A' => '#22c55e',
                                'C' => '#ef4444',
                                'N' => '#a855f7',
                                'O' => '#0ea5e9'
                            ];
                        @endphp
                        
                        @foreach($dimensionOrder as $index => $dimCode)
                            @php
                                $itemsInDim = [];
                                foreach($namaVariabel as $idx => $item) {
                                    if($item['dim'] === $dimCode) {
                                        $itemsInDim[$idx] = $item;
                                    }
                                }
                                $dimColor = $dimensionColors[$dimCode];
                                $isFirstDim = $index === 0;
                            @endphp
                            
                            <div class="var-dimension-header {{ $isFirstDim ? 'active' : '' }}" onclick="toggleDimension(this, event)" style="border-color: {{ $dimColor }}22;">
                                <div class="var-dimension-badge" style="background: {{ $dimColor }}22; color: {{ $dimColor }};">{{ $dimCode }}</div>
                                <span style="color: {{ $dimColor }}; flex: 1;">{{ $dimensionLabels[$dimCode] }}</span>
                                <span style="font-size: 11px; color: #94a3b8; font-weight: 600;">{{ count($itemsInDim) }} item</span>
                            </div>
                            
                            <div class="var-items-container {{ $isFirstDim ? 'active' : '' }}">
                                @foreach($itemsInDim as $i => $v)
                                    @php
                                        $skor = $jawaban[$i] ?? 0;
                                        if (!empty($v['reverse'])) {
                                            $displaySkor = 6 - $skor;
                                        } else {
                                            $displaySkor = $skor;
                                        }
                                        $pct  = $displaySkor * 20;
                                        $badgeClass = $dimColor === '#ef4444' ? 'badge-kel' : ($dimColor === '#a855f7' ? 'badge-dep' : 'badge-pre');
                                        $statusColor = $displaySkor >= 4 ? '#ef4444' : ($displaySkor >= 3 ? '#f97316' : '#22c55e');
                                    @endphp
                                    <div class="var-item" style="border-left-color: {{ $dimColor }};">
                                        <span class="var-badge {{ $badgeClass }}">{{ $v['dim'] }}</span>
                                        <span class="var-label">{!! $v['label'] . (!empty($v['reverse']) ? ' <span style="color:#9ca3af; font-size:10px; font-weight:600;">(R)</span>' : '') !!}</span>
                                        <div class="var-bar-wrap">
                                            <div class="var-bar-track">
                                                <div class="var-bar-fill" style="width:{{ $pct }}%; background:{{ $dimColor }};"></div>
                                            </div>
                                        </div>
                                        <span class="var-score">{{ $skor }}/5</span>
                                        <div class="var-dot-status" style="background:{{ $statusColor }}"></div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Logika Pakar - Simplified -->
            <div class="logika-card">
                <div class="logika-header">
                    <div class="logika-title">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16" color="#3b82f6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Hasil Analisis
                    </div>
                    <span class="cf-badge">Skor: {{ $edasScore !== null ? number_format($edasScore, 2) . '%' : number_format($total, 1) . '%' }}</span>
                </div>

                <!-- Hasil Utama -->
                <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 2px solid #0284c7; border-radius: 14px; padding: 22px 24px; margin-bottom: 18px; text-align: center;">
                    <div style="font-size: 12px; color: #0c4a6e; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 8px; text-transform: uppercase;">Hasil Diagnosis (Metode EDAS)</div>
                    <div style="font-size: 28px; font-weight: 900; color: #0369a1; margin-bottom: 4px;">{{ $risikoLabel }}</div>
                    <div style="font-size: 12px; color: #0c4a6e; line-height: 1.5;">Berdasarkan perbandingan profil OCEAN Anda dengan target risiko</div>
                </div>

                <!-- Tombol Detail -->
                <button class="xai-toggle-btn" onclick="toggleXaiDetails()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="13" height="13" id="xai-toggle-icon" style="transform: rotate(0deg);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    Lihat Detail Perhitungan
                </button>

                <div class="xai-details" id="xai-details-content">
                    <div style="font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">📊 Detail Perhitungan Metode EDAS</div>
                    <p style="font-size: 11px; color: #64748b; margin-bottom: 12px; line-height: 1.6;">
                        Metode EDAS membandingkan skor kepribadian OCEAN Anda dengan 3 profil target (Rendah, Sedang, Tinggi). Kolom "Cocok" menunjukkan persentase kesamaan (semakin tinggi = semakin mirip profil tersebut). <strong>Kategori risiko dengan nilai kecocokan tertinggi adalah diagnosis Anda.</strong>
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

                    function toggleDimension(header, event) {
                        // Find the next sibling var-items-container
                        var container = header.nextElementSibling;
                        if (!container || !container.classList.contains('var-items-container')) return;

                        var isActive = header.classList.contains('active');

                        if (isActive) {
                            // Collapse
                            header.classList.remove('active');
                            container.classList.remove('active');
                        } else {
                            // Expand this one
                            header.classList.add('active');
                            container.classList.add('active');
                        }
                    }
                </script>

                {{-- ===== CARA MEMBACA HASIL + METODOLOGI ===== --}}
                <div class="cara-baca-wrap">
                    <div class="cara-baca-header" onclick="toggleCaraBaca(this)" id="cara-baca-toggle">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Cara Membaca Hasil Diagnosis
                        <span class="caret">▼</span>
                    </div>
                    <div class="cara-baca-body" id="cara-baca-body">
                        <div class="risk-guide-grid">
                            {{-- Risiko Rendah --}}
                            <div class="risk-guide-item rendah">
                                <div class="rg-icon">✅</div>
                                <div class="rg-label">Risiko Rendah</div>
                                <div class="rg-desc">Kondisi psikologis Anda sehat. Pertahankan kebiasaan positif dan keseimbangan akademik-istirahat.</div>
                            </div>
                            {{-- Risiko Sedang --}}
                            <div class="risk-guide-item sedang">
                                <div class="rg-icon">⚠️</div>
                                <div class="rg-label">Risiko Sedang</div>
                                <div class="rg-desc">Ada tanda-tanda kelelahan. Perlu perhatian lebih pada manajemen waktu dan istirahat yang cukup.</div>
                            </div>
                            {{-- Risiko Tinggi --}}
                            <div class="risk-guide-item tinggi">
                                <div class="rg-icon">🚨</div>
                                <div class="rg-label">Risiko Tinggi</div>
                                <div class="rg-desc">Burnout sudah signifikan. Segera cari dukungan konselor kampus dan kurangi beban aktivitas.</div>
                            </div>
                        </div>

                        {{-- Metodologi Badges --}}
                        <div class="metode-row">
                            <span class="metode-label">📚 Metode:</span>
                            <a href="https://ipip.ori.org" target="_blank" class="metode-badge blue">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="11" height="11"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                Big Five IPIP-20
                            </a>
                            <span class="metode-badge violet">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="11" height="11"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Metode EDAS
                            </span>
                            <span class="metode-badge teal">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="11" height="11"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                SPK Burnout IT
                            </span>
                        </div>
                    </div>
                </div>

                <script>
                    function toggleCaraBaca(header) {
                        var body = document.getElementById('cara-baca-body');
                        var isOpen = header.classList.contains('open');
                        if (isOpen) {
                            header.classList.remove('open');
                            body.classList.remove('open');
                        } else {
                            header.classList.add('open');
                            body.classList.add('open');
                        }
                    }
                </script>
            </div>

            <!-- Rekomendasi -->
            <div class="rekom-card">
                <div class="rekom-title">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16" color="#f59e0b">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    Rekomendasi
                </div>

                @if(!empty($aiRekomendasi) && $aiRekomendasi !== '-')
                <div class="rekom-alert" style="background:#f0f9ff; border-color:#38bdf8;">
                    <div class="saran-label" style="color:#0ea5e9;">🤖 Rekomendasi AI Berdasarkan Diagnosis {{ $risikoLabel }}:</div>
                    <div class="ai-rekom-content" style="color:#334155; font-size:13px; line-height:1.75;">{!! $aiRekomendasiHtml !!}</div>
                </div>
                @else
                    {{-- Fallback berdasarkan hasil EDAS ($risikoLabel), bukan $total numerik --}}
                    @if($risikoLabel === 'RISIKO TINGGI')
                    <div class="rekom-alert tinggi">
                        <div class="saran-label">💡 Saran Utama (Risiko Tinggi):</div>
                        <p>Kondisi Anda menunjukkan risiko burnout yang <strong>tinggi</strong>. Segera kurangi beban aktivitas, cari dukungan konselor kampus, dan prioritaskan kesehatan mental Anda sebelum kondisi memburuk.</p>
                    </div>
                    @elseif($risikoLabel === 'RISIKO SEDANG')
                    <div class="rekom-alert sedang">
                        <div class="saran-label">💡 Saran Utama (Risiko Sedang):</div>
                        <p>Risiko burnout Anda berada di level <strong>sedang</strong>. Terapkan manajemen waktu yang lebih baik, istirahat yang cukup, dan hindari menumpuk tugas di last minute agar kondisi tidak semakin buruk.</p>
                    </div>
                    @else
                    <div class="rekom-alert rendah">
                        <div class="saran-label rendah">✅ Saran Utama (Risiko Rendah):</div>
                        <p class="rendah">Kondisi psikologis Anda <strong>baik</strong>. Pertahankan kebiasaan positif, jaga keseimbangan aktivitas akademik dan istirahat, serta tetap terhubung dengan lingkungan sosial kampus.</p>
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

            {{-- REKOMENDASI --}}
            <div style="font-size:13px; font-weight:700; color:#1e293b; margin-bottom:10px; padding-bottom:4px; border-bottom:2px solid #e2e8f0;">
                Rekomendasi
            </div>
            @php
                $hasAi = !empty($aiRekomendasi) && $aiRekomendasi !== '-';
                $rekomBg = $hasAi ? '#f0f9ff' : ($total >= 70 ? '#fef2f2' : ($total >= 40 ? '#fffbeb' : '#f0fdf4'));
                $rekomBorder = $hasAi ? '#38bdf8' : ($total >= 70 ? '#fecaca' : ($total >= 40 ? '#fde68a' : '#bbf7d0'));
                $rekomTextCol = $hasAi ? '#334155' : ($total >= 70 ? '#7f1d1d' : ($total >= 40 ? '#78350f' : '#166534'));
                $rekomLabelCol = $hasAi ? '#0ea5e9' : ($total >= 70 ? '#dc2626' : ($total >= 40 ? '#92400e' : '#166534'));
                $rekomLabel = $hasAi ? '🤖 Rekomendasi Hasil Diagnosis:' : '💡 Saran Utama:';
            @endphp
            <div style="background:{{ $rekomBg }}; border:1px solid {{ $rekomBorder }}; border-radius:8px; padding:14px 16px; margin-bottom:16px;">
                <div style="font-size:11px; font-weight:700; color:{{ $rekomLabelCol }}; margin-bottom:6px;">{{ $rekomLabel }}</div>
                <div style="font-size:11.5px; color:{{ $rekomTextCol }}; margin:0; line-height:1.6;">
                    @if($hasAi)
                        {!! nl2br(e($aiRekomendasi)) !!}
                    @else
                        @if($total >= 70)
                            Kondisi Anda menunjukkan tingkat burnout yang tinggi. Disarankan untuk segera melakukan penyesuaian pola aktivitas dan mencari dukungan profesional agar kondisi tidak semakin memburuk.
                        @elseif($total >= 40)
                            Tingkat burnout Anda berada di level sedang. Perlu manajemen waktu yang lebih baik dan istirahat yang cukup untuk mencegah kondisi memburuk.
                        @else
                            Kondisi Anda masih baik. Tetap jaga keseimbangan aktivitas dan istirahat yang cukup untuk mempertahankan kondisi ini.
                        @endif
                    @endif
                </div>
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
