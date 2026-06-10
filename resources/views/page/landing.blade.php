{{-- resources/views/landing.blade.php --}}
@extends('layouts.landing')

@section('content')

    {{-- ===== HERO SECTION ===== --}}
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Sistem Pakar Deteksi dan Rekomendasi <span class="text-blue">Burnout</span> Mahasiswa Jurusan JTI</h1>
                    <p class="subtitle">
                        Platform gratis berbasis pakar untuk mendeteksi tingkat burnout dan memberikan
                        rekomendasi personal guna meningkatkan kesehatan mental mahasiswa.
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('kuesioner.dummy') }}" class="btn-primary">Mulai Tes</a>
                        <a href="#about" class="btn-outline">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
                <div class="hero-visual">
                    <img src="{{ asset('assets/images/burnout.png') }}" alt="Ilustrasi JTISmartCare" class="main-hero-img">
            </div>
        </div>
    </section>

    {{-- ===== ABOUT SECTION ===== --}}
    <section id="about">
        <div class="container">
            <div class="about-content">
                <h2 class="section-title">
                    Tentang Kami <span class="text-blue">JTI</span> <span class="text-green">SmartCare</span>
                </h2>

                <p class="about-text">
                    Perjalanan kuliah adalah petualangan luar biasa, namun terkadang juga penuh tekanan.
                    JTI SmartCare hadir sebagai platform berbasis Sistem Pakar yang cerdas dan empati,
                    siap membantu Anda mendeteksi tanda-tanda burnout lebih dini.
                </p>

                <div class="visi-misi-container">
                    <div class="card-about card-visi">
                        <div class="card-header">
                            <div class="icon-circle">
                                <img src="{{ asset('assets/images/icon-binoculars.png') }}" alt="Icon Visi">
                            </div>
                            <h3>Visi</h3>
                        </div>
                        <p>Menjadi platform deteksi dini burnout terdepan yang membantu mahasiswa
                            mewujudkan kehidupan kampus yang sehat, seimbang, dan bermakna.
                        </p>
                    </div>
                    <div class="card-about card-misi">
                        <div class="card-header">
                            <div class="icon-circle">
                                <img src="{{ asset('assets/images/icon-target.png') }}" alt="Icon Misi">
                            </div>
                            <h3>Misi</h3>
                        </div>
                        <ul class="misi-list">
                            <li>Memberikan deteksi burnout yang akurat dan cepat.</li>
                            <li>Menyediakan rekomendasi personal untuk mahasiswa.</li>
                            <li>Menciptakan komunikasi yang peduli kesehatan mental.</li>
                            <li>Meningkatkan awareness tentang pentingnya mental health.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CARA KERJA SECTION ===== --}}
    <section id="cara-kerja">
        <div class="container">
            <h2 class="step-title">
                Cara Kerja <span class="text-blue">JTI</span> <span class="text-green">SmartCare</span>
            </h2>
            <p class="steps-subtitle">
                Proses sederhana untuk mengetahui kondisi mental Anda dan mendapatkan bantuan yang tepat
            </p>
            <div class="steps">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <div class="step-icon">
                        <img src="assets/images/icon-kuesioner.png" alt="Icon Kuesioner">
                    </div>
                    <h3>Isi Kuesioner</h3>
                    <p>Jawab beberapa pertanyaan seputar kondisi Anda saat ini.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">02</div>
                    <div class="step-icon">
                        <img src="assets/images/icon-brain.png" alt="Icon Brain">
                    </div>
                    <h3>Analisis Sistem Pakar</h3>
                    <p>Sistem kami menganalisis jawaban berdasarkan data dan pengetahuan.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">03</div>
                    <div class="step-icon">
                        <img src="assets/images/icon-kuesioner.png" alt="Icon Kuesioner">
                    </div>
                    <h3>Hasil Deteksi</h3>
                    <p>Dapatkan hasil tingkat burnout Anda secara real-time.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">04</div>
                    <div class="step-icon">
                        <img src="assets/images/icon-communication.png" alt="Icon communication">
                    </div>
                    <h3>Rekomendasi Personal</h3>
                    <p>Terima saran dan tips untuk mengatasi burnout sesuai kondisi Anda.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== ARTICLES SECTION ===== --}}
    <section id="articles">
        <div class="articles">
            <div class="container">
                {{-- Bagian Atas: Hero Artikel (Teks Kiri, Visual Kanan) --}}
                <div class="articles-hero">
                    <div class="hero-articles">
                        <h2 class="section-title-articles">
                            Rekomendasi Bacaan Untuk Hidup yang <span class="text-blue">Lebih Sehat</span>
                        </h2>
                        <p class="articles-subtitle">
                            Temukan berbagai artikel pilihan seputar kesehatan mental, manajemen stres, produktivitas, dan
                            pengembangan diri yang dikurasi khusus untuk mendukung perjalananmu.
                        </p>
                        <a href="#articles" class="btn-all-articles">
                            Lihat Semua Artikel  <span class="arrow">  →</span>
                        </a>
                    </div>

                    <div class="hero-visual">
                        <img src="{{ asset('assets/images/articles.png') }}" alt="Ilustrasi Membaca JTISmartCare"
                            class="main-hero-img">
                    </div>
                </div>

                {{-- Bagian Bawah: Card Grid --}}
                <div id="articles"class="card-grid">
                    <article class="article-card">
                        <div class="article-card-img">
                            <img src="{{ asset('assets/images/gambar1.png') }}" alt="Burnout">
                        </div>
                        <div class="article-card-body">
                            <h3>Ciri-Ciri Burnout dan Cara Mengatasinya</h3>
                            <p>Kenali burnout lebih dalam dan temukan cara efektif untuk memulihkan diri.</p>
                            <a href="https://www.alodokter.com/ciri-ciri-burnout-dan-cara-mengatasinya" class="read-more">Baca Selengkapnya <span>→</span></a>
                        </div>
                    </article>

                    <article class="article-card">
                        <div class="article-card-img">
                            <img src="{{ ('assets/images/gambar2.png') }}" alt="Work-Life Balance">
                        </div>
                        <div class="article-card-body">
                            <h3>Tips Jaga Work-Life Balance</h3>
                            <p>Jangan biarkan tugas menguras hidupmu. Simak 4 strategi efektif menyeimbangkan waktu.</p>
                            <a href="https://www.gramedia.com/blog/hindari-burnout-ini-4-tips-work-life-balance-yang-efektif/" class="read-more">Baca Selengkapnya <span>→</span></a>
                        </div>
                    </article>

                    <article class="article-card">
                        <div class="article-card-img">
                            <img src="{{ asset('assets/images/gambar3.png') }}" alt="Atasi Stres">
                        </div>
                        <div class="article-card-body">
                            <h3>5 Tips Jitu Atasi Burnout Mahasiswa</h3>
                            <p>Panduan praktis bagi mahasiswa yang merasa kewalahan dengan beban akademik.</p>
                            <a href="https://keslan.kemkes.go.id/view_artikel/2946/pengaruh-burnout-akademik-pada-kesehatan-mental-mahasiswa" class="read-more">Baca Selengkapnya <span>→</span></a>
                        </div>
                    </article>

                    <article class="article-card">
                        <div class="article-card-img">
                            <img src="{{ asset('assets/images/gambar4.png') }}" alt="Mental Health">
                        </div>
                        <div class="article-card-body">
                            <h3>Burnout & Kesehatan Mental</h3>
                            <p>Pahami bagaimana tekanan akademik berlebih mempengaruhi kondisi psikologis Anda.</p>
                           <a href="https://teropongmedia.id/5-tips-jitu-mengatasi-burnout-pada-mahasiswa/" class="read-more">Baca Selengkapnya <span>→</span></a>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

@endsection