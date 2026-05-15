@extends('app')

@section('title', 'Dashboard')

@section('content')

    <style>
        .dashboard-first {
            padding: 10px 5px 30px;
        }

        /* HEADER */

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .dashboard-title {
            font-size: 30px;
            font-weight: 700;
            color: #355872;
            margin-bottom: 6px;
            font-family: 'Sora', sans-serif;
        }

        .dashboard-subtitle {
            font-size: 14px;
            color: #355872;
            margin: 0;
        }

        .dashboard-date {
            background: #E8F1FF;
            padding: 9px 20px;
            border-radius: 15px;
            font-size: 13px;
            font-weight: 600;
            color: #2563EB;
        }

        /* STATS */

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid #edf2f7;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: #E8F1FF;
            color: #2563eb;
        }

        .stat-icon.orange {
            background: #FFF4E8;
            color: #f97316;
        }

        .stat-icon.green {
            background: #E8FFF1;
            color: #16a34a;
        }

        .stat-card h3 {
            font-size: 24px;
            margin: 0;
            color: #355872;
            font-weight: 700;
        }

        .stat-card span {
            display: block;
            font-size: 14px;
            color: #475569;
            font-weight: 600;
            margin-top: 3px;
        }

        .stat-card small {
            color: #94a3b8;
            font-size: 12px;
        }

        /* JOURNEY */

        .journey-section {
            background: #ffffff;
            border-radius: 24px;
            padding: 28px;
            border: 1px solid #edf2f7;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            margin-bottom: 25px;
        }

        .section-header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 25px;
            flex: 1;
        }

        .section-line {
            width: 5px;
            height: 60px;
            border-radius: 10px;
            background: #2563eb;
        }

        .section-header h3 {
            font-size: 24px;
            font-weight: 700;
            color: #355872;
            margin-bottom: 5px;
        }

        .section-header p {
            color: #355872;
            font-size: 14px;
            margin: 0;
        }

        .journey-card {
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px;
            transition: 0.3s;
            background: #fff;
        }

        .journey-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.08);
        }

        .step-number {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .journey-card h4 {
            font-size: 17px;
            font-weight: 700;
            color: #355872;
            margin-bottom: 10px;
        }

        .journey-card p {
            font-size: 13px;
            color: #355872;
            line-height: 1.7;
            min-height: 70px;
        }

        .journey-icon i {
            font-size: 48px;
            color: #60a5fa;
        }

        /* Pastikan grid tetap 3 kolom pada layar besar */
        .journey-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
            align-items: stretch;
            /* Membuat tinggi card sama rata */
        }

        /* Mengatur ukuran container ikon agar seragam */
        .journey-icon {
            text-align: center;
            margin-top: 20px;
            height: 80px;
            /* Tentukan tinggi tetap agar gambar sejajar */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .journey-icon img {
            max-height: 100%;
            /* Gambar tidak akan melebihi tinggi container */
            width: auto;
            object-fit: contain;
        }

        /* PRIVACY */

        .privacy-section {
            background: linear-gradient(135deg, #eff6ff, #f8fbff);
            border-radius: 24px;
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            border: 1px solid #dbeafe;
        }

        .privacy-left {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .privacy-icon {
            width: 55px;
            height: 55px;
            border-radius: 16px;
            background: #dbeafe;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .privacy-left h4 {
            font-size: 18px;
            font-weight: 700;
            color: #355872;
            margin-bottom: 5px;
        }

        .privacy-left p {
            font-size: 14px;
            color: #355872;
            margin: 0;
            line-height: 1.7;
        }

        .privacy-right i {
            font-size: 80px;
            color: #93c5fd;
        }

        .privacy-right img {
            width: 220px;
            height: auto;
        }

        .journey-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .btn-kuesioner {
            background: #2563eb;
            color: #fff;
            padding: 12px 24px;
            border-radius: 14px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            transition: 0.3s;
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.20);

            margin-left:auto;
        }

        .btn-kuesioner:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        /* RESPONSIVE */

        @media(max-width:992px) {

            .dashboard-stats {
                grid-template-columns: 1fr;
            }

            .journey-grid {
                grid-template-columns: 1fr;
            }

            .privacy-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .privacy-right {
                display: block;
                margin: 15px auto 0;
            }

            .privacy-right img {
                width: 50px;
                /* Lebih kecil untuk layar HP */
            }
        }

        @media(max-width:576px) {

            .dashboard-title {
                font-size: 24px;
            }

            .section-header {
                flex-direction: column;
            }

            .section-line {
                width: 100%;
                height: 5px;
            }

        }
    </style>

    <div class="dashboard-first">

        {{-- HEADER --}}
        <div class="dashboard-header">

            <div>
                <h2 class="dashboard-title">
                    Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }} 👋
                </h2>

                <p class="dashboard-subtitle">
                    Mulai perjalanan kesehatan mentalmu bersama JTI SmartCare
                </p>
            </div>

            <div class="dashboard-date">
                <i class="bi bi-calendar3 me-2"></i>
                {{ now()->locale('id')->isoFormat('D MMMM Y') }}
            </div>

        </div>

        {{-- STATISTICS --}}
        <div class="dashboard-stats">

            <div class="stat-card">

                <div class="stat-icon blue">
                    <i class="bi bi-journal-text"></i>
                </div>

                <div>
                    <h3>0</h3>
                    <span>Total Tes Diagnosis</span>
                    <small>Sejak pertama kali</small>
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon orange">
                    <i class="bi bi-fire"></i>
                </div>

                <div>
                    <h3>-</h3>
                    <span>Status Burnout</span>
                    <small>Tingkat Resiko</small>
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon green">
                    <i class="bi bi-calendar-check"></i>
                </div>

                <div>
                    <h3>-</h3>
                    <span>Terakhir Cek</span>
                    <small>Belum ada data</small>
                </div>

            </div>

        </div>

        {{-- JOURNEY --}}
        <div class="journey-section">
            <div class="section-header">
                <div class="section-line"></div>
                <div>
                    <h3>Mulai Perjalananmu di JTI SmartCare</h3>
                    <p>Ikuti langkah berikut untuk mendapatkan insight dan rekomendasi terbaik untukmu.</p>
                </div>
                <a href="{{ route('diagnosis.form') }}" class="btn-kuesioner">
                    <i class="fa-solid fa-clipboard-question"></i>
                    Isi Kuesioner
                </a>
            </div>

            <div class="journey-grid">
                {{-- STEP 1 --}}
                <div class="journey-card">
                    <div class="step-number">1</div>
                    <h4>Isi Kuesioner</h4>
                    <p>Jawab beberapa pertanyaan untuk mengetahui kondisi burnout-mu saat ini.</p>
                    <div class="journey-icon">
                        <img src="{{ asset('assets/images/icon-kuesioner1.png') }}" alt="Icon Kuesioner">
                    </div>
                </div> {{-- Pastikan penutup ini ada --}}

                {{-- STEP 2 --}}
                <div class="journey-card">
                    <div class="step-number">2</div>
                    <h4>Lihat Hasil Diagnosis</h4>
                    <p>Dapatkan hasil analisis dan pahami tingkat risiko burnout secara detail.</p>
                    <div class="journey-icon">
                        <img src="{{ asset('assets/images/icon-document.png') }}" alt="Icon Document">
                    </div>
                </div>

                {{-- STEP 3 --}}
                <div class="journey-card">
                    <div class="step-number">3</div>
                    <h4>Ikuti Rekomendasi</h4>
                    <p>Terima rekomendasi personal untuk membantu menjaga kesehatan mentalmu.</p>
                    <div class="journey-icon">
                        <img src="{{ asset('assets/images/icon-relax.png') }}" alt="Icon Relax">
                    </div>
                </div>
            </div>
        </div>

        {{-- PRIVACY --}}
        <div class="privacy-section">

            <div class="privacy-left">

                <div class="privacy-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>

                <div>
                    <h4>Privasi & Kerahasiaan Terjamin</h4>
                    <p>
                        Semua data dan jawabanmu bersifat rahasia dan hanya digunakan untuk analisis serta rekomendasi yang
                        tepat.
                    </p>

                </div>

            </div>

            <div class="privacy-right">
                <img src="{{ asset('assets/images/icon-privacy.png') }}" alt="Icon Privacy">
            </div>

        </div>

    </div>

@endsection