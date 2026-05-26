{{-- ============================================================ --}}
{{-- STEP 6 - VIEW                                               --}}
{{-- FILE: resources/views/user/riwayatdiagnosis.blade.php      --}}
{{-- CARA: GANTI SELURUH isi file yang lama dengan kode ini     --}}
{{-- ============================================================ --}}

@extends('app')

@section('content')

<style>
.page-title {
    font-size: 22px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}
.breadcrumb-link {
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
}
.breadcrumb-link:hover { text-decoration: underline; }
.breadcrumb-sep { color: #94a3b8; margin: 0 4px; }
.breadcrumb-cur { color: #64748b; }

/* STAT CARDS */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 20px;
}
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 18px 20px;
    box-shadow: 0 1px 6px rgba(0,0,0,.06);
    display: flex;
    align-items: center;
    gap: 14px;
}
.stat-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.stat-info .stat-val {
    font-size: 22px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}
.stat-info .stat-label {
    font-size: 11px;
    color: #64748b;
    margin-top: 3px;
}

/* TABEL RIWAYAT */
.riwayat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 6px rgba(0,0,0,.06);
}
.riwayat-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}
.riwayat-card-title {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
}
.btn-kuesioner {
    background: #1d4ed8;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    font-size: 12.5px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}
.btn-kuesioner:hover { background: #1e40af; color: white; }

/* TABLE */
.riwayat-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12.5px;
}
.riwayat-table thead tr {
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
}
.riwayat-table th {
    padding: 10px 14px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.riwayat-table td {
    padding: 12px 14px;
    border-bottom: 1px solid #f1f5f9;
    color: #374151;
    vertical-align: middle;
}
.riwayat-table tbody tr:hover {
    background: #f8fafc;
}
.riwayat-table tbody tr:last-child td {
    border-bottom: none;
}

/* BADGE RISIKO */
.badge-risiko {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
.badge-tinggi { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.badge-sedang { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
.badge-rendah { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }

/* OCEAN MINI BARS */
.ocean-mini {
    display: flex;
    gap: 3px;
    align-items: flex-end;
    height: 20px;
}
.ocean-bar {
    width: 8px;
    border-radius: 2px;
    min-height: 3px;
}

/* AKSI BUTTONS */
.btn-lihat {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    padding: 5px 12px;
    font-size: 11.5px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    cursor: pointer;
}
.btn-lihat:hover { background: #dbeafe; color: #1d4ed8; }

.btn-hapus {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
    border-radius: 6px;
    padding: 5px 10px;
    font-size: 11.5px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.btn-hapus:hover { background: #fee2e2; }

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 48px 20px;
}
.empty-state .empty-icon {
    font-size: 48px;
    margin-bottom: 12px;
    opacity: 0.4;
}
.empty-state h5 {
    font-size: 15px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 6px;
}
.empty-state p {
    font-size: 12.5px;
    color: #94a3b8;
    margin-bottom: 16px;
}

/* ALERT SUCCESS */
.alert-success-custom {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 12px 16px;
    color: #166534;
    font-size: 12.5px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

@media (max-width: 768px) {
    .stat-grid { grid-template-columns: 1fr; }
    .riwayat-table { display: block; overflow-x: auto; }
}
</style>

<div class="sbcare-wrapper">
    <main class="sbcare-main">

        {{-- PAGE HEADER --}}
        <div style="margin-bottom: 20px;">
            <h4 class="page-title">Riwayat Diagnosis</h4>
            <small>
                <a href="{{ route('dashboard') }}" class="breadcrumb-link">Dashboard</a>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-cur">Riwayat Diagnosis</span>
            </small>
        </div>

        {{-- ALERT SUCCESS (setelah hapus) --}}
        @if(session('success'))
        <div class="alert-success-custom">
            ✅ {{ session('success') }}
        </div>
        @endif

        {{-- STAT CARDS --}}
        @php
            $totalDiagnosis = $riwayat->count();
            $risikoTerakhir = $riwayat->first()?->kategori_risiko ?? '-';
            $skorTerakhir   = $riwayat->first()?->risk_index ?? '-';

            $badgeClass = match(true) {
                str_contains(strtoupper($risikoTerakhir), 'TINGGI') => 'badge-tinggi',
                str_contains(strtoupper($risikoTerakhir), 'SEDANG') => 'badge-sedang',
                default => 'badge-rendah',
            };
        @endphp

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #eff6ff;">📋</div>
                <div class="stat-info">
                    <div class="stat-val">{{ $totalDiagnosis }}</div>
                    <div class="stat-label">Total Diagnosis</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fffbeb;">🔥</div>
                <div class="stat-info">
                    <div class="stat-val" style="font-size:14px;">{{ $risikoTerakhir }}</div>
                    <div class="stat-label">Status Terakhir</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #f0fdf4;">📊</div>
                <div class="stat-info">
                    <div class="stat-val">{{ $skorTerakhir }}{{ $skorTerakhir !== '-' ? '%' : '' }}</div>
                    <div class="stat-label">Skor Terakhir</div>
                </div>
            </div>
        </div>

        {{-- TABEL RIWAYAT --}}
        <div class="riwayat-card">
            <div class="riwayat-card-header">
                <div class="riwayat-card-title">Semua Riwayat Diagnosis</div>
                <a href="{{ route('diagnosis.form') }}" class="btn-kuesioner">
                    ➕ Diagnosis Baru
                </a>
            </div>

            @if($riwayat->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h5>Belum Ada Riwayat</h5>
                <p>Kamu belum pernah melakukan diagnosis burnout.<br>Mulai sekarang untuk memantau kondisi mentalmu.</p>
                <a href="{{ route('diagnosis.form') }}" class="btn-kuesioner" style="display:inline-flex; margin: 0 auto;">
                    Mulai Diagnosis Pertama
                </a>
            </div>

            @else
            {{-- TABEL DATA --}}
            <table class="riwayat-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Risk Index</th>
                        <th>Status Risiko</th>
                        <th>Profil OCEAN</th>
                        <th>Rekomendasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayat as $i => $item)
                    @php
                        $aspek = $item->aspek_psikologi ?? [];
                        $kategori = strtoupper($item->kategori_risiko ?? '');
                        $badgeCls = match(true) {
                            str_contains($kategori, 'TINGGI') => 'badge-tinggi',
                            str_contains($kategori, 'SEDANG') => 'badge-sedang',
                            default => 'badge-rendah',
                        };
                        $icon = match(true) {
                            str_contains($kategori, 'TINGGI') => '🔴',
                            str_contains($kategori, 'SEDANG') => '🟡',
                            default => '🟢',
                        };
                        $warnaOcean = [
                            'openness'          => '#0ea5e9',
                            'conscientiousness' => '#ef4444',
                            'extraversion'      => '#eab308',
                            'agreeableness'     => '#22c55e',
                            'neuroticism'       => '#a855f7',
                        ];
                    @endphp
                    <tr>
                        {{-- NO --}}
                        <td style="color:#94a3b8; font-weight:600;">
                            {{ $i + 1 }}
                        </td>

                        {{-- TANGGAL --}}
                        <td>
                            <div style="font-weight:600; color:#1e293b;">
                                {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->isoFormat('D MMM YYYY') }}
                            </div>
                            <div style="font-size:11px; color:#94a3b8;">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
                            </div>
                        </td>

                        {{-- RISK INDEX --}}
                        <td>
                            <span style="font-size:18px; font-weight:800; color:#1e293b;">
                                {{ $item->risk_index ?? $item->total_skor }}
                            </span>
                            <span style="font-size:11px; color:#94a3b8;">/100</span>
                        </td>

                        {{-- STATUS RISIKO --}}
                        <td>
                            <span class="badge-risiko {{ $badgeCls }}">
                                {{ $icon }} {{ $item->kategori_risiko }}
                            </span>
                        </td>

                        {{-- PROFIL OCEAN (mini bar chart) --}}
                        <td>
                            <div class="ocean-mini" title="O-C-E-A-N">
                                @foreach(['openness','conscientiousness','extraversion','agreeableness','neuroticism'] as $key)
                                @php
                                    $persen = $aspek[$key]['persen'] ?? 0;
                                    $tinggi = max(3, round($persen / 100 * 20));
                                @endphp
                                <div class="ocean-bar"
                                     style="height:{{ $tinggi }}px; background:{{ $warnaOcean[$key] }};"
                                     title="{{ strtoupper($key[0]) }}: {{ $persen }}%">
                                </div>
                                @endforeach
                            </div>
                            <div style="font-size:9px; color:#94a3b8; margin-top:2px;">O C E A N</div>
                        </td>

                        {{-- REKOMENDASI (preview) --}}
                        <td style="max-width: 200px;">
                            <div style="font-size:11.5px; color:#64748b; line-height:1.4;
                                        overflow:hidden; display:-webkit-box;
                                        -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                                {{ $item->rekomendasi_ai ?? $item->rekomendasi ?? '-' }}
                            </div>
                        </td>

                        {{-- AKSI --}}
                        <td>
                            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                {{-- Lihat Detail --}}
                                <a href="{{ route('diagnosis.detail', $item->id) }}" class="btn-lihat">
                                    👁 Lihat
                                </a>

                                {{-- Hapus --}}
                                <form method="POST"
                                      action="{{ route('history.destroy', $item->id) }}"
                                      onsubmit="return confirm('Hapus riwayat diagnosis ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-hapus">🗑</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

        </div>

    </main>
</div>

@endsection