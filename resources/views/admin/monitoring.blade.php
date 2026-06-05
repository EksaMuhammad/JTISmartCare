@extends('appadmin')

@section('content')
<div class="container-fluid py-4 px-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Monitoring & Laporan Diagnosis</h3>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Pantau perkembangan kondisi burnout mahasiswa dari waktu ke waktu melalui data
                diagnosis yang komprehensif.
            </p>
        </div>

        <button class="btn text-white px-4 py-2 shadow-sm"
            style="background: #5B5FEF; border-radius: 12px;">
            <i class="bi bi-download me-2"></i> Unduh Laporan
        </button>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4"
        style="border-radius: 18px; background: #F8F7FD;">

        <div class="card-body">

            <form method="GET" action="{{ route('admin.monitoring') }}">

                <div class="row g-3 align-items-center">

                    <!-- Search -->
                    <div class="col-md-5">
                        <div class="position-relative">

                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>

                            <input type="text"
                                name="search"
                                class="form-control ps-5"
                                placeholder="Cari Nama Mahasiswa atau NIM..."
                                value="{{ request('search') }}"
                                style="height: 48px; border-radius: 12px;">

                        </div>
                    </div>

                    <!-- Select -->
                    <div class="col-md-3">
                        <select name="tingkat"
                            class="form-select"
                            style="height: 48px; border-radius: 12px;">

                            <option value="">Semua Tingkat Burnout</option>

                            <option value="Ringan"
                                {{ request('tingkat') == 'Ringan' ? 'selected' : '' }}>
                                Ringan
                            </option>

                            <option value="Sedang"
                                {{ request('tingkat') == 'Sedang' ? 'selected' : '' }}>
                                Sedang
                            </option>

                            <option value="Berat"
                                {{ request('tingkat') == 'Berat' ? 'selected' : '' }}>
                                Berat
                            </option>

                        </select>
                    </div>

                    <!-- Date -->
                    <div class="col-md-3">
                        <input type="text"
                            name="tanggal"
                            class="form-control"
                            value="{{ request('tanggal') }}"
                            placeholder="01/05/2024 - 31/05/2024"
                            style="height: 48px; border-radius: 12px;">
                    </div>

                    <!-- Tombol Filter -->
                    <div class="col-md-1">
                        <button type="submit"
                            class="btn w-100"
                            style="height: 48px; border-radius: 12px; background: #ECEAF8;">

                            <i class="bi bi-funnel"></i>

                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm"
        style="border-radius: 18px; overflow: hidden;">

        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle mb-0">

                <thead style="background: #F4F2FC;">
                    <tr class="text-muted text-uppercase"
                        style="font-size: 12px; letter-spacing: .5px;">
                        <th class="ps-4 py-3">No</th>
                        <th>Mahasiswa</th>
                        <th>NIM</th>
                        <th>Jurusan</th>
                        <th>Tingkat Burnout</th>
                        <th>Hasil</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($data as $item)

                    <tr style="vertical-align: middle;">

                        <!-- No -->
                        <td class="ps-4">
                            {{ $loop->iteration }}
                        </td>

                        <!-- Nama -->
                        <td>
                            <div class="fw-semibold">
                                {{ $item->user->name ?? $item->nama }}
                            </div>
                        </td>

                        <!-- NIM -->
                        <td class="text-muted">
                            {{ $item->user->nim ?? $item->nim }}
                        </td>

                        <!-- Jurusan -->
                        <td class="text-muted">
                            {{ $item->user->jurusan ?? $item->jurusan }}
                        </td>

                        <!-- Tingkat Burnout -->
                        <td>

                            @if(in_array($item->kategori_risiko, ['RISIKO TINGGI', 'Tinggi']))
                                <span class="badge rounded-pill px-3 py-2"
                                    style="background: #FEE2E2; color: #DC2626;">
                                    Tinggi
                                </span>

                            @elseif(in_array($item->kategori_risiko, ['RISIKO SEDANG', 'Sedang']))
                                <span class="badge rounded-pill px-3 py-2"
                                    style="background: #FEF3C7; color: #D97706;">
                                    Sedang
                                </span>

                            @else
                                <span class="badge rounded-pill px-3 py-2"
                                    style="background: #DCFCE7; color: #16A34A;">
                                    Ringan
                                </span>
                            @endif

                        </td>

                        <!-- Hasil -->
                        <td>

                            <div class="d-flex align-items-center gap-2">

                                <span style="font-size: 13px;">
                                    {{ $item->risk_index }}%
                                </span>

                                <div style="
                                    width: 40px;
                                    height: 4px;
                                    background: #E5E7EB;
                                    border-radius: 10px;
                                    overflow: hidden;
                                ">

                                    <div style="
                                        width: {{ $item->risk_index }}%;
                                        height: 100%;
                                        background:
                                            {{ $item->risk_index >= 70 ? '#DC2626' :
                                            ($item->risk_index >= 40 ? '#F59E0B' : '#16A34A') }};
                                    "></div>

                                </div>

                            </div>

                        </td>

                        <!-- Tanggal -->
                        <td class="text-muted" style="font-size: 13px;">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                        </td>

                        <!-- Aksi -->
                        <td class="text-center">

                            <button class="btn btn-sm text-white px-3"
                                style="
                                    background: #818CF8;
                                    border-radius: 8px;
                                    font-size: 12px;
                                ">

                                Detail

                            </button>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="8">

                            <!-- Empty State -->
                            <div class="d-flex flex-column align-items-center justify-content-center py-5">

                                <div class="mb-3 d-flex align-items-center justify-content-center"
                                    style="
                                        width: 110px;
                                        height: 110px;
                                        border-radius: 50%;
                                        background: #F1F0FB;
                                    ">

                                    <i class="bi bi-folder2-open"
                                        style="font-size: 42px; color: #B5B3D9;"></i>

                                </div>

                                <h6 class="fw-semibold mb-1">
                                    Belum ada data mahasiswa terinput
                                </h6>

                                <p class="text-muted text-center mb-0"
                                    style="max-width: 320px; font-size: 14px;">

                                    Data monitoring akan muncul di sini setelah
                                    mahasiswa menyelesaikan sesi diagnosis psikologis.

                                </p>

                            </div>

                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="d-flex justify-content-between align-items-center px-4 py-3"
            style="background: #FAF9FE;">

            <small class="text-muted">
                Menampilkan data mahasiswa
            </small>

            <!-- Pagination -->
            <div class="d-flex align-items-center gap-2">

                <button class="btn btn-sm border-0 bg-transparent text-muted">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <button class="btn btn-sm text-white"
                    style="background: #5B5FEF;
                           width: 32px;
                           height: 32px;
                           border-radius: 10px;">
                    1
                </button>

                <button class="btn btn-sm border-0 bg-transparent text-muted">
                    <i class="bi bi-chevron-right"></i>
                </button>

            </div>

        </div>

    </div>

</div>
@endsection