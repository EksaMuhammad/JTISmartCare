@extends('appadmin')

@section('title', 'Dashboard Admin')

@section('content')

{{-- TOPBAR --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

    <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Cari data, laporan, atau mahasiswa...">
    </div>

    <div class="d-flex align-items-center gap-3">
        <button class="notif-btn">
            <i class="bi bi-bell"></i>
        </button>

        <div class="top-user">
            <span>Dashboard</span>

            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f8fafc&color=355872&bold=true">
        </div>
    </div>
</div>

{{-- WELCOME --}}
<div class="mb-4">
    <h2 class="fw-bold mb-1">Selamat datang, Admin!</h2>

    <p class="text-muted mb-0">
        Berikut adalah ringkasan performa kesehatan mental mahasiswa hari ini.
    </p>
</div>

{{-- CARD STATISTIK --}}
<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="dashboard-card">

            <div class="stat-top">
                <div class="icon purple">
                    <i class="bi bi-people-fill"></i>
                </div>

                <span>-- %</span>
            </div>

            <div class="stat-title">
                Jumlah Mahasiswa
            </div>

            <div class="stat-number">0</div>

        </div>
    </div>

    <div class="col-md-3">
        <div class="dashboard-card">

            <div class="stat-top">
                <div class="icon soft">
                    <i class="bi bi-clipboard2-pulse-fill"></i>
                </div>

                <span>-- %</span>
            </div>

            <div class="stat-title">
                Jumlah Diagnosis
            </div>

            <div class="stat-number">0</div>

        </div>
    </div>

    <div class="col-md-3">
        <div class="dashboard-card">

            <div class="stat-top">
                <div class="icon red">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <span>-- %</span>
            </div>

            <div class="stat-title">
                Kasus Burnout Tinggi
            </div>

            <div class="stat-number">0</div>

        </div>
    </div>

    <div class="col-md-3">
        <div class="dashboard-card">

            <div class="stat-top">
                <div class="icon green">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>

                <span>-- %</span>
            </div>

            <div class="stat-title">
                Rata-rata Skor Burnout
            </div>

            <div class="stat-number">
                - <small>/ 5</small>
            </div>

        </div>
    </div>

</div>

{{-- CHART --}}
<div class="row g-4 mb-4">

    <div class="col-lg-4">

        <div class="chart-card">

            <h5 class="chart-title">
                Distribusi Tingkat Burnout
            </h5>

            <div class="empty-chart">

                <div class="circle-chart">
                    <i class="bi bi-bar-chart"></i>
                </div>

                <h6>Belum ada data tersedia</h6>

                <p>
                    Data akan muncul setelah mahasiswa mulai mengisi kuesioner
                </p>

            </div>

        </div>

    </div>

    <div class="col-lg-8">

        <div class="chart-card">

            <h5 class="chart-title">
                Perkembangan Diagnosis per Bulan
            </h5>

            <div class="line-placeholder">

                <div class="empty-data">
                    <strong>Belum ada data tersedia</strong>

                    <small>
                        Data akan muncul setelah mahasiswa mulai mengisi kuesioner
                    </small>
                </div>

                <div class="month-list">
                    <span>JAN</span>
                    <span>FEB</span>
                    <span>MAR</span>
                    <span>APR</span>
                    <span>MEI</span>
                    <span>JUN</span>
                    <span>JUL</span>
                    <span>AGU</span>
                    <span>SEP</span>
                    <span>OKT</span>
                    <span>NOV</span>
                    <span>DES</span>
                </div>

            </div>

        </div>

    </div>

</div>

{{-- STATUS --}}
<div class="status-card">

    <div class="d-flex align-items-center gap-3">

        <div class="status-icon">
            <i class="bi bi-hourglass-split"></i>
        </div>

        <div>
            <h6 class="mb-1 fw-bold">
                Menunggu Data Masuk
            </h6>

            <small>
                Belum ada analisis yang dapat ditampilkan saat ini.
            </small>
        </div>

    </div>

    <button class="detail-btn">
        Lihat Laporan Detail
    </button>

</div>

@endsection
@section('styles')
<style>

    .search-box{
        flex:1;
        max-width:540px;
        position:relative;
    }

    .search-box i{
        position:absolute;
        left:15px;
        top:50%;
        transform:translateY(-50%);
        color:#94a3b8;
    }

    .search-box input{
        width:100%;
        border:none;
        background:#f1f5f9;
        height:46px;
        border-radius:14px;
        padding:0 18px 0 42px;
        font-size:14px;
    }

    .search-box input:focus{
        outline:none;
    }

    .notif-btn{
        width:42px;
        height:42px;
        border:none;
        border-radius:12px;
        background:#fff;
        box-shadow:0 2px 10px rgba(0,0,0,0.04);
    }

    .top-user{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .top-user span{
        font-size:14px;
        font-weight:600;
        color:#4f46e5;
    }

    .top-user img{
        width:36px;
        height:36px;
        border-radius:50%;
    }

    /* ===== CARD ===== */

    .dashboard-card{
        background:#fff;
        border-radius:18px;
        padding:20px;
        box-shadow:0 2px 10px rgba(0,0,0,0.04);
        height:100%;
    }

    .stat-top{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    }

    .stat-top span{
        color:#cbd5e1;
        font-size:13px;
        font-weight:700;
    }

    .icon{
        width:42px;
        height:42px;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:18px;
    }

    .icon.purple{
        background:#ede9fe;
        color:#7c3aed;
    }

    .icon.soft{
        background:#eef2ff;
        color:#6366f1;
    }

    .icon.red{
        background:#fee2e2;
        color:#dc2626;
    }

    .icon.green{
        background:#dcfce7;
        color:#16a34a;
    }

    .stat-title{
        font-size:13px;
        color:#64748b;
        margin-bottom:6px;
    }

    .stat-number{
        font-size:36px;
        font-weight:800;
        line-height:1;
    }

    /* ===== CHART ===== */

    .chart-card{
        background:#fff;
        border-radius:18px;
        padding:20px;
        box-shadow:0 2px 10px rgba(0,0,0,0.04);
        height:100%;
    }

    .chart-title{
        font-weight:700;
        margin-bottom:20px;
    }

    .empty-chart{
        min-height:280px;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        text-align:center;
    }

    .circle-chart{
        width:110px;
        height:110px;
        border-radius:50%;
        border:10px solid #ede9fe;

        display:flex;
        align-items:center;
        justify-content:center;

        margin-bottom:18px;

        color:#c4b5fd;
        font-size:30px;
    }

    .empty-chart h6{
        font-weight:700;
        margin-bottom:6px;
    }

    .empty-chart p{
        max-width:220px;
        color:#64748b;
        font-size:14px;
    }

    .line-placeholder{
        min-height:280px;
        border:2px solid #d6d3d1;
        border-radius:12px;
        padding:20px;
        display:flex;
        flex-direction:column;
        justify-content:center;
        position:relative;
    }

    .empty-data{
        width:320px;
        max-width:100%;
        background:#f3f4f6;
        border-radius:12px;
        padding:16px;
        text-align:center;
        margin:auto;
    }

    .empty-data small{
        display:block;
        color:#64748b;
    }

    .month-list{
        display:flex;
        justify-content:space-between;
        margin-top:30px;
        font-size:12px;
        color:#475569;
    }

    /* ===== STATUS ===== */

    .status-card{
        background:#b7c7d3;
        border-radius:16px;
        padding:20px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        gap:20px;
    }

    .status-icon{
        font-size:28px;
        color:#355872;
    }

    .detail-btn{
        border:none;
        background:#6b8395;
        color:#fff;
        padding:12px 18px;
        border-radius:10px;
        font-size:14px;
        font-weight:600;
    }

    @media(max-width:768px){

        .top-user span{
            display:none;
        }

        .status-card{
            flex-direction:column;
            align-items:flex-start;
        }

    }

</style>
@endsection