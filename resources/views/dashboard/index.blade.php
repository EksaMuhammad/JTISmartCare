@extends('app')

@section('title', 'Dashboard')

@section('content')
@php
    $score = $latestDiagnosis ? $latestDiagnosis->risk_index : 0;
    $statusText = 'Belum Ada';
    $statusColor = '#64748b';
    $statusBg = '#f1f5f9';
    if ($latestDiagnosis) {
        if ($latestDiagnosis->kategori_risiko === 'RISIKO TINGGI' || $latestDiagnosis->kategori_risiko === 'Tinggi') {
            $statusText = 'Tinggi';
            $statusColor = '#dc2626';
            $statusBg = '#fee2e2';
        } elseif ($latestDiagnosis->kategori_risiko === 'RISIKO SEDANG' || $latestDiagnosis->kategori_risiko === 'Sedang') {
            $statusText = 'Sedang';
            $statusColor = '#ea580c';
            $statusBg = '#ffedd5';
        } else {
            $statusText = 'Ringan';
            $statusColor = '#16a34a';
            $statusBg = '#dcfce7';
        }
    }

    $neuroPct = $latestDiagnosis->aspek_psikologi['neuroticism']['persen'] ?? 0;
    $conscPct = 100 - ($latestDiagnosis->aspek_psikologi['conscientiousness']['persen'] ?? 0);
    $extraPct = 100 - ($latestDiagnosis->aspek_psikologi['extraversion']['persen'] ?? 0);
    $agreePct = 100 - ($latestDiagnosis->aspek_psikologi['agreeableness']['persen'] ?? 0);
@endphp

<div class="d-flex justify-content-between align-items-center mb-1">
    <h2 style="font-family:'Sora',sans-serif;font-weight:700;color:#1e293b;font-size:1.8rem;margin:0;">
        Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }}! 
    </h2>
    <div style="background:#d1d5db;color:#475569;font-size:0.8rem;font-weight:600;padding:6px 14px;border-radius:20px;">
        {{ now()->locale('id')->isoFormat('D MMMM Y') }}
    </div>
</div>
<p style="color:#64748b;font-size:0.95rem;margin-bottom:24px;">Pantau kesehatan mental Anda dan dapatkan rekomendasi personal</p>

{{-- STAT CARDS --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100" style="box-shadow:0 2px 10px rgba(0,0,0,0.04);border:1px solid #f1f5f9;border-radius:16px;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:50px;height:50px;background:#e0f2fe;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#0284c7;font-size:1.3rem;">
                    <i class="bi bi-book"></i>
                </div>
                <div>
                    <div style="font-size:1.2rem;font-weight:700;color:#1e293b;line-height:1.2;">{{ $totalDiagnosis }}</div>
                    <div style="font-size:0.8rem;font-weight:600;color:#475569;">Total Tes Diagnosis</div>
                    <div style="font-size:0.7rem;color:#94a3b8;">Sejak pertama kali</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100" style="box-shadow:0 2px 10px rgba(0,0,0,0.04);border:1px solid #f1f5f9;border-radius:16px;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:50px;height:50px;background:{{ $statusBg }};border-radius:50%;display:flex;align-items:center;justify-content:center;color:{{ $statusColor }};font-size:1.3rem;">
                    <i class="bi bi-fire"></i>
                </div>
                <div>
                    <div style="font-size:1.1rem;font-weight:700;color:{{ $statusColor }};line-height:1.2;">{{ $statusText }}</div>
                    <div style="font-size:0.8rem;font-weight:600;color:#475569;">Status Burnout</div>
                    <div style="font-size:0.7rem;color:#94a3b8;">Tingkat Resiko</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100" style="box-shadow:0 2px 10px rgba(0,0,0,0.04);border:1px solid #f1f5f9;border-radius:16px;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:50px;height:50px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#16a34a;font-size:1.3rem;">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div>
                    <div style="font-size:1.1rem;font-weight:700;color:#1e293b;line-height:1.2;">
                        {{ $latestDiagnosis ? \Carbon\Carbon::parse($latestDiagnosis->created_at)->locale('id')->isoFormat('D MMMM Y') : '-' }}
                    </div>
                    <div style="font-size:0.8rem;font-weight:600;color:#475569;">Terakhir cek</div>
                    <div style="font-size:0.7rem;color:#94a3b8;">
                        {{ $latestDiagnosis ? \Carbon\Carbon::parse($latestDiagnosis->created_at)->locale('id')->diffForHumans() : 'Belum ada data' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MAIN GRID --}}
<div class="row g-4">
    {{-- Hasil Diagnosis Burnout --}}
    <div class="col-lg-6">
        <div class="card h-100" style="border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <div class="card-header-custom" style="padding:20px 24px 10px;">Hasil Diagnosis Burnout</div>
            <div class="card-body" style="padding:15px 24px 24px;">
                <div class="d-flex gap-3">
                    <div style="flex:1;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="background:{{ $statusBg }};border-radius:8px;padding:4px 8px;font-size:0.85rem;font-weight:700;color:{{ $statusColor }};display:inline-flex;align-items:center;gap:6px;">
                                <span></span> Resiko Burnout : {{ $statusText }}
                            </div>
                        </div>
                        <p style="font-size:0.82rem;color:#64748b;line-height:1.6;margin-bottom:16px;text-align:justify;">
                            @if($latestDiagnosis && !empty($latestDiagnosis->rekomendasi_ai) && $latestDiagnosis->rekomendasi_ai !== '-')
                                {{ Str::limit($latestDiagnosis->rekomendasi_ai, 180) }}
                            @else
                                @if($statusText === 'Tinggi')
                                    Kondisi Anda saat ini menunjukkan tingkat burnout yang tinggi. Disarankan untuk segera mencari bantuan profesional (konselor/psikolog) dan meluangkan waktu untuk istirahat guna memulihkan kesehatan mental Anda.
                                @elseif($statusText === 'Sedang')
                                    Anda menunjukkan tanda-tanda kelelahan akademik dan kurang istirahat. Segera lakukan manajemen stres dan istirahat yang cukup untuk mencegah burnout meningkat.
                                @else
                                    Tingkat stres dan burnout Anda tergolong rendah. Tetap jaga keseimbangan antara kegiatan akademik dan waktu istirahat untuk mempertahankan kondisi prima Anda.
                                @endif
                            @endif
                        </p>
                        <a href="{{ route('history.index') }}" class="btn btn-outline-primary w-100" style="border-radius:10px;font-size:0.85rem;font-weight:600;display:flex;align-items:center;justify-content:center;gap:6px;border-color:#e2e8f0;color:#3b82f6;">
                            <i class="bi bi-file-earmark-text"></i> Lihat penjelasan lengkap
                        </a>
                    </div>
                    <div style="width:130px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                        <div style="position:relative;width:110px;height:110px;">
                            <svg width="110" height="110" viewBox="0 0 110 110">
                                <circle cx="55" cy="55" r="45" fill="none" stroke="#f1f5f9" stroke-width="12"/>
                                <circle cx="55" cy="55" r="45" fill="none"
                                    stroke="{{ $statusColor }}"
                                    stroke-width="12"
                                    stroke-dasharray="{{ 2 * pi() * 45 }}"
                                    stroke-dashoffset="{{ 2 * pi() * 45 * (1 - $score / 100) }}"
                                    stroke-linecap="round"
                                    transform="rotate(-90 55 55)"/>
                            </svg>
                            <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                <div style="font-family:'Sora',sans-serif;font-size:1.4rem;font-weight:800;color:#1e293b;line-height:1;">{{ $score }}%</div>
                                <div style="font-size:0.55rem;color:#64748b;">Burnout Score</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pengingat dan Aktivitas --}}
    <div class="col-lg-6">
        <div class="card h-100" style="border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <div class="card-header-custom d-flex align-items-center gap-2" style="padding:20px 24px 10px;">
                <i class="bi bi-bell"></i> Pengingat dan Aktivitas
            </div>
            <div class="card-body d-flex flex-column" style="padding:15px 24px 24px;">
                @if(!$latestDiagnosis || \Carbon\Carbon::parse($latestDiagnosis->created_at)->diffInDays() >= 7)
                    <div style="background:#eff6ff;border-radius:12px;padding:16px;margin-bottom:16px;position:relative;">
                        <div class="d-flex gap-3">
                            <i class="bi bi-bell-fill" style="color:#3b82f6;font-size:1.1rem;margin-top:2px;"></i>
                            <div>
                                <div style="font-size:0.85rem;font-weight:700;color:#1e3a8a;margin-bottom:4px;">Anda belum mengisi kuesioner minggu ini</div>
                                <div style="font-size:0.75rem;color:#3b82f6;margin-bottom:12px;">Yuk, isi kuesioner untuk memantau kondisi Anda.</div>
                                <a href="{{ route('diagnosis.form') }}" class="btn btn-primary w-100" style="background:#2563eb;border:none;border-radius:8px;font-size:0.85rem;font-weight:600;">
                                    Isi Kuesioner Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div style="background:#e0f2fe;border-radius:12px;padding:16px;margin-bottom:16px;position:relative;">
                        <div class="d-flex gap-3">
                            <i class="bi bi-check-circle-fill" style="color:#0284c7;font-size:1.1rem;margin-top:2px;"></i>
                            <div>
                                <div style="font-size:0.85rem;font-weight:700;color:#0369a1;margin-bottom:4px;">Kuesioner Terisi Minggu Ini</div>
                                <div style="font-size:0.75rem;color:#0284c7;margin-bottom:12px;">Terima kasih telah memantau kesehatan mental Anda secara berkala.</div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div style="border:1px solid #f1f5f9;border-radius:12px;padding:16px;display:flex;align-items:center;justify-content:space-between;background:#fafaf9;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:30px;height:30px;background:#dcfce7;color:#16a34a;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <div style="font-size:0.85rem;font-weight:700;color:#1e293b;">Terakhir mengisi kuesioner</div>
                            <div style="font-size:0.8rem;color:#64748b;">
                                {{ $latestDiagnosis ? \Carbon\Carbon::parse($latestDiagnosis->created_at)->locale('id')->diffForHumans() . ' (' . \Carbon\Carbon::parse($latestDiagnosis->created_at)->locale('id')->isoFormat('D MMM Y') . ')' : 'Belum pernah' }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('history.index') }}" style="color:#94a3b8;font-size:1.2rem;">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Rekomendasi Untuk Anda --}}
    <div class="col-lg-6">
        <div class="card h-100" style="border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <div class="card-header-custom" style="padding:20px 24px 15px;">Rekomendasi Untuk Anda</div>
            <div class="card-body" style="padding:0 24px 24px;">
                
                @php
                    $saranDashboard = [];
                    if ($latestDiagnosis) {
                        $aspek = is_string($latestDiagnosis->aspek_psikologi) 
                                 ? json_decode($latestDiagnosis->aspek_psikologi, true) 
                                 : ($latestDiagnosis->aspek_psikologi ?? []);
                        
                        foreach ($aspek as $key => $a) {
                            $persen = $a['persen'] ?? 0;
                            if ($key === 'neuroticism' && $persen >= 60) {
                                $saranDashboard[] = ['icon' => 'bi-heart-pulse', 'color' => 'ef4444', 'bg' => 'fef2f2', 'title' => 'Kelola stres dan kecemasan', 'desc' => 'Terapkan teknik relaksasi dan jangan ragu istirahat saat kewalahan.'];
                            }
                            if ($key === 'conscientiousness' && $persen <= 45) {
                                $saranDashboard[] = ['icon' => 'bi-list-check', 'color' => 'eab308', 'bg' => 'fefce8', 'title' => 'Tingkatkan manajemen waktu', 'desc' => 'Pecah tugas besar menjadi langkah-langkah kecil agar lebih ringan.'];
                            } elseif ($key === 'conscientiousness' && $persen >= 75) {
                                $saranDashboard[] = ['icon' => 'bi-shield-exclamation', 'color' => 'eab308', 'bg' => 'fefce8', 'title' => 'Waspada Perfeksionisme', 'desc' => 'Ingatlah untuk tidak bekerja terlalu keras sampai melupakan istirahat.'];
                            }
                            if ($key === 'extraversion' && $persen <= 35) {
                                $saranDashboard[] = ['icon' => 'bi-people', 'color' => '3b82f6', 'bg' => 'eff6ff', 'title' => 'Bangun dukungan sosial', 'desc' => 'Sesekali berkolaborasi atau berdiskusi dengan teman tentang kesulitan Anda.'];
                            } elseif ($key === 'extraversion' && $persen >= 75) {
                                $saranDashboard[] = ['icon' => 'bi-person-dash', 'color' => '3b82f6', 'bg' => 'eff6ff', 'title' => 'Hindari Over-commit', 'desc' => 'Anda sangat aktif, pastikan fokus pada tujuan utama agar tidak kelelahan.'];
                            }
                            if ($key === 'agreeableness' && $persen >= 75) {
                                $saranDashboard[] = ['icon' => 'bi-slash-circle', 'color' => '8b5cf6', 'bg' => 'f5f3ff', 'title' => 'Belajar menetapkan batasan', 'desc' => 'Beranilah berkata "tidak" agar tidak kelelahan membantu tugas orang lain.'];
                            } elseif ($key === 'agreeableness' && $persen <= 35) {
                                $saranDashboard[] = ['icon' => 'bi-emoji-smile', 'color' => '8b5cf6', 'bg' => 'f5f3ff', 'title' => 'Tingkatkan kerja sama tim', 'desc' => 'Lebih berempati pada teman untuk menghindari konflik yang menguras energi.'];
                            }
                            if ($key === 'openness' && $persen >= 75) {
                                $saranDashboard[] = ['icon' => 'bi-focus', 'color' => '10b981', 'bg' => 'ecfdf5', 'title' => 'Fokus pada satu hal', 'desc' => 'Hindari "shiny object syndrome", fokus pada satu/dua teknologi dulu.'];
                            }
                        }
                    }
                    
                    // Default recommendations if no extreme traits
                    if (count($saranDashboard) == 0) {
                        $saranDashboard = [
                            ['icon' => 'bi-check', 'color' => '16a34a', 'bg' => 'f0fdf4', 'title' => 'Istirahat minimal 7 - 8 jam', 'desc' => 'Tidur cukup membantu pemulihan energi dan fokus.'],
                            ['icon' => 'bi-clock', 'color' => 'eab308', 'bg' => 'fefce8', 'title' => 'Atur prioritas kegiatan', 'desc' => 'Fokus pada satu tugas dalam satu waktu untuk mengurangi multitasking.'],
                            ['icon' => 'bi-people-fill', 'color' => 'ef4444', 'bg' => 'fef2f2', 'title' => 'Konsultasi profesional', 'desc' => 'Jika merasa kewalahan, jangan ragu mencari bantuan.']
                        ];
                    }
                    
                    $saranDashboard = array_slice($saranDashboard, 0, 3);
                @endphp

                @foreach($saranDashboard as $idx => $saran)
                <div class="d-flex align-items-start gap-3 p-3 mb-{{ $idx === 2 ? '3' : '2' }}" style="background:#{{ $saran['bg'] }};border-radius:12px;">
                    <div style="width:28px;height:28px;background:#{{ $saran['color'] }};color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:{{ $idx === 0 ? '1rem' : '0.9rem' }};flex-shrink:0;">
                        <i class="bi {{ $saran['icon'] }}"></i>
                    </div>
                    <div>
                        <div style="font-size:0.85rem;font-weight:700;color:#1e293b;margin-bottom:2px;">{{ $saran['title'] }}</div>
                        <div style="font-size:0.75rem;color:#475569;">{{ $saran['desc'] }}</div>
                    </div>
                </div>
                @endforeach

                <a href="{{ route('history.index') }}" class="btn w-100" style="background:#fff;border:1px solid #e2e8f0;color:#64748b;font-weight:600;font-size:0.85rem;border-radius:10px;padding:10px;text-decoration:none;display:inline-block;text-align:center;">
                    <i class="bi bi-card-text me-1"></i> Lihat Semua Rekomendasi
                </a>

            </div>
        </div>
    </div>

    {{-- Faktor Penyebab Dominan --}}
    <div class="col-lg-6">
        <div class="card h-100" style="border:1px solid #e2e8f0;border-radius:16px;box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <div class="card-header-custom" style="padding:20px 24px 15px;">Faktor Penyebab Dominan</div>
            <div class="card-body" style="padding:0 24px 24px;">
                
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:36px;height:36px;background:#eff6ff;color:#3b82f6;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <div style="flex:1;">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:0.85rem;color:#334155;font-weight:500;">Kelelahan Akademik</span>
                            <span style="font-size:0.85rem;color:#ef4444;font-weight:700;">{{ $conscPct }}%</span>
                        </div>
                        <div class="progress" style="height:6px;background:#f1f5f9;border-radius:3px;">
                            <div class="progress-bar" style="width:{{ $conscPct }}%;background:#ef4444;border-radius:3px;"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:36px;height:36px;background:#f0fdf4;color:#16a34a;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                        <i class="bi bi-moon-stars-fill"></i>
                    </div>
                    <div style="flex:1;">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:0.85rem;color:#334155;font-weight:500;">Kurang Tidur / Stres</span>
                            <span style="font-size:0.85rem;color:#ea580c;font-weight:700;">{{ $neuroPct }}%</span>
                        </div>
                        <div class="progress" style="height:6px;background:#f1f5f9;border-radius:3px;">
                            <div class="progress-bar" style="width:{{ $neuroPct }}%;background:#ea580c;border-radius:3px;"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:36px;height:36px;background:#faf5ff;color:#a855f7;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <div style="flex:1;">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:0.85rem;color:#334155;font-weight:500;">Tekanan Sosial</span>
                            <span style="font-size:0.85rem;color:#eab308;font-weight:700;">{{ $agreePct }}%</span>
                        </div>
                        <div class="progress" style="height:6px;background:#f1f5f9;border-radius:3px;">
                            <div class="progress-bar" style="width:{{ $agreePct }}%;background:#eab308;border-radius:3px;"></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="width:36px;height:36px;background:#fdf2f8;color:#ec4899;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                        <i class="bi bi-suit-heart-fill"></i>
                    </div>
                    <div style="flex:1;">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:0.85rem;color:#334155;font-weight:500;">Kondisi Emosional</span>
                            <span style="font-size:0.85rem;color:#10b981;font-weight:700;">{{ $extraPct }}%</span>
                        </div>
                        <div class="progress" style="height:6px;background:#f1f5f9;border-radius:3px;">
                            <div class="progress-bar" style="width:{{ $extraPct }}%;background:#10b981;border-radius:3px;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
