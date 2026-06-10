@extends('app')

@section('content')
<div class="container mt-4">

    {{-- Page Title + Breadcrumb --}}
    <h2 class="page-title">Form Kuesioner Burnout</h2>
    <nav class="breadcrumb-custom mb-3">
        <span>Form Kuesioner</span>
    </nav>

    {{-- Instruksi Card --}}
    <div class="instruksi-card mb-3">
        <div class="instruksi-left">
            <div class="instr-title">Instruksi Pengisian</div>
            <p>
                Jawab <strong>20 pernyataan</strong> berikut secara jujur sesuai gambaran diri Anda secara umum.
                Pilih tingkat persetujuan yang paling mencerminkan diri Anda.
                <br><small style="color:#777; margin-top:4px; display:block;">
                    📚 Standar <strong>IPIP-20 (Big Five Personality)</strong> - <a href="https://ipip.ori.org" target="_blank" style="color:#1a6fa8; text-decoration:none;">ipip.ori.org</a>
                </small>
            </p>
        </div>
        <div class="estimasi-box">
            <span class="est-label">Estimasi Waktu</span>
            <div class="est-val">5 Menit</div>
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('diagnosis.proses') }}">
        @csrf

        {{-- Progress Bar --}}
        <div class="progress mb-3" style="height: 8px; border-radius: 99px;">
            <div id="progressBar" class="progress-bar" role="progressbar" style="width:0%; border-radius: 99px; background:#1a73e8;"></div>
        </div>
        <div id="progressLabel" class="text-end mb-3" style="font-size:11px; color:#888;">0 / 20 terjawab</div>

        <div id="questions"></div>

        {{-- Bottom Info Bar --}}
        <div class="info-bar mt-3">
            <div class="info-bar-text">
                <div class="info-title">Sudah mengisi semua pertanyaan?</div>
                <div class="info-sub">Klik tombol untuk memproses diagnosis menggunakan mesin inferensi SBP.</div>
            </div>
            <button type="submit" id="btnSubmit" class="btn-diagnosa" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right:6px;">
                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/>
                </svg>
                Proses Diagnosis
            </button>
        </div>

    </form>

</div>

<style>
/* Hilangkan sidebar hanya di halaman ini */
.sidebar{
    display:none !important;
}

.main-content{
    margin-left:0 !important;
    width:100% !important;
    max-width:100% !important;
    padding:30px !important;
}

.sidebar-toggle{
    display:none !important;
}

/* ===== PAGE TITLE & BREADCRUMB ===== */
.page-title {
    font-size: 26px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 4px;
}
.breadcrumb-custom {
    font-size: 13px;
    list-style: none;
    padding: 0;
}
.breadcrumb-custom a {
    color: #1a6fa8;
    text-decoration: none;
}
.breadcrumb-custom span {
    color: #888;
}

/* ===== INSTRUKSI CARD ===== */
.instruksi-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}
.instruksi-left .instr-title {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 6px;
}
.instruksi-left p {
    font-size: 12px;
    color: #555;
    line-height: 1.6;
    margin: 0;
}
.estimasi-box {
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    padding: 10px 18px;
    text-align: center;
    flex-shrink: 0;
    min-width: 110px;
}
.estimasi-box .est-label {
    font-size: 11px;
    color: #888;
    display: block;
    margin-bottom: 4px;
}
.estimasi-box .est-val {
    font-size: 22px;
    font-weight: 700;
    color: #1a3a5c;
}

/* ===== QUESTION CARD ===== */
.card-question {
    background: #fff;
    border-radius: 12px;
    padding: 14px 16px;
    margin-bottom: 10px;
    border: 1px solid #e0e0e0;
}
.q-top {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 12px;
}
.q-num {
    min-width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 500;
    color: #666;
    flex-shrink: 0;
    margin-top: 1px;
}
.q-content { flex: 1; }
.category {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.04em;
    margin-bottom: 2px;
}
.question-title {
    font-size: 13px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 2px;
}
.desc {
    font-size: 11px;
    color: #888;
}

/* ===== SCALE ===== */
.scale-wrap {
    position: relative;
    padding-bottom: 4px;
}
.scale-line {
    position: absolute;
    top: 9px;
    left: 10%;
    right: 10%;
    height: 3px;
    border-radius: 99px;
    background: linear-gradient(to right, #4caf50, #8bc34a, #ffc107, #ff9800, #f44336);
    z-index: 0;
}
.scale {
    display: flex;
    justify-content: space-between;
    position: relative;
    z-index: 1;
}
.scale label {
    flex: 1;
    text-align: center;
    cursor: pointer;
}
.scale input[type=radio] {
    display: none;
}
.circle {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin: 0 auto 5px;
    border: 2px solid #fff;
    outline: 2px solid transparent;
    transition: outline 0.15s, transform 0.15s;
}
.scale input:checked ~ .circle {
    outline: 2.5px solid #222;
    outline-offset: 1px;
    transform: scale(1.2);
}
.label-title {
    font-size: 10px;
    font-weight: 600;
    color: #1a1a1a;
}
.label-desc {
    font-size: 9px;
    color: #888;
    line-height: 1.3;
}

/* warna dot */
.green       { background: #4caf50; }
.light-green { background: #8bc34a; }
.yellow      { background: #ffc107; }
.orange      { background: #ff9800; }
.red         { background: #f44336; }

/* ===== BOTTOM INFO BAR ===== */
.info-bar {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.info-bar-text .info-title {
    font-size: 13px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 3px;
}
.info-bar-text .info-sub {
    font-size: 11px;
    color: #888;
}
.btn-diagnosa {
    background: #1a3a5c;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    white-space: nowrap;
    flex-shrink: 0;
    text-decoration: none;
}
.btn-diagnosa:hover {
    background: #122840;
    color: #fff;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const colors = ["green", "light-green", "yellow", "orange", "red"];

    const oldQuestions = [
        {
            cat: "BEBAN AKADEMIK",
            catColor: "#F91414",
            title: "Beban Tugas Kuliah",
            desc: "Seberapa berat beban tugas kuliah yang kamu rasakan saat ini?",
            opts: [["Sangat Ringan","Tidak ada tekanan"],["Ringan","Mudah dikelola"],["Sedang","Masih bisa ditangani"],["Berat","Sering kewalahan"],["Sangat Berat","Hampir tidak tertangani"]]
        },
        {
            cat: "PEMULIHAN FISIK",
            catColor: "#0EA5E9",
            title: "Kualitas dan Kuantitas Tidur",
            desc: "Bagaimana kualitas tidur kamu dalam 2 minggu terakhir?",
            opts: [["Sangat Baik","7-8 jam, nyenyak"],["Baik","6-7 jam, cukup nyenyak"],["Cukup","5-6 jam Kadang terganggu"],["Buruk","4-5 jam Sering terganggu"],["Sangat Buruk","< 4 jam sering insomnia"]]
        },
        {
            cat: "MOTIVASI & PRESTASI",
            catColor: "#EAB308",
            title: "Motivasi Belajar",
            desc: "Seberapa besar motivasi kamu untuk belajar dan mengerjakan tugas saat ini?",
            opts: [["Sangat Tinggi","Penuh semangat"],["Tinggi","Masih termotivasi"],["Cukup","Naik turun"],["Rendah","Sering malas"],["Sangat Rendah","Tidak ada motivasi"]]
        },
        {
            cat: "DUKUNGAN SOSIAL",
            catColor: "#22C55E",
            title: "Dukungan Sosial",
            desc: "Seberapa besar dukungan dari keluarga, teman, atau orang sekitar?",
            opts: [["Sangat Baik","Selalu didukung"],["Baik","Cukup didukung"],["Cukup","Kadang ada"],["Buruk","Jarang ada"],["Sangat Buruk","Merasa sendirian"]]
        },
        {
            cat: "PEMULIHAN FISIK",
            catColor: "#0EA5E9",
            title: "Kondisi Fisik dan Kesehatan",
            desc: "Bagaimana kondisi fisik dan kesehatanmu secara umum dalam 2 minggu ini?",
            opts: [["Sangat Baik","Tidak ada keluhan"],["Baik","Sangat baik"],["Cukup","Sedikit keluhan"],["Buruk","Sering tidak fit"],["Sangat Buruk","Sering sakit"]]
        },
        {
            cat: "DUKUNGAN FINANSIAL",
            catColor: "#22C55E",
            title: "Tekanan Keuangan",
            desc: "Seberapa besar tekanan finansial yang kamu rasakan sehari-hari?",
            opts: [["Tidak Ada","Aman finansial"],["Kecil","Masih terkendali"],["Sedang","Cukup khawatir"],["Besar","Sering stres"],["Sangat Besar","Sangat tertekan"]]
        },
        {
            cat: "REGULASI EMOSI",
            catColor: "#A855F7",
            title: "Keseimbangan Emosi",
            desc: "Seberapa stabil kondisi emosi kamu — apakah mudah marah, sedih, atau cemas?",
            opts: [["Sangat Stabil","Tenang & terkontrol"],["Stabil","Cukup baik"],["Cukup Stabil","Kadang terganggu"],["Tidak Stabil","Sering berubah"],["Sangat Tidak Stabil","Sulit dikendalikan"]]
        },
        {
            cat: "MOTIVASI & PRESTASI",
            catColor: "#EAB308",
            title: "Kepuasan Prestasi Akademik",
            desc: "Seberapa puas kamu dengan pencapaian akademik yang telah kamu raih?",
            opts: [["Sangat Puas","Melampaui target"],["Puas","Sesuai harapan"],["Cukup Puas","Hampir sesuai"],["Tidak Puas","Di bawah harapan"],["Sangat Tidak Puas","Jauh dari target"]]
        },
        {
            cat: "BEBAN AKADEMIK",
            catColor: "#F91414",
            title: "Manajemen Waktu",
            desc: "Seberapa baik kamu mengelola waktu antara kuliah, istirahat, dan aktivitas lainnya?",
            opts: [["Sangat Baik","Terorganisir"],["Baik","Cukup teratur"],["Cukup","Kadang terlambat"],["Buruk","Sering keteteran"],["Sangat Buruk","Tidak terorganisir"]]
        },
        {
            cat: "KECEMASAN",
            catColor: "#A855F7",
            title: "Kecemasan Masa Depan",
            desc: "Seberapa sering kamu merasa cemas tentang masa depan dan karier setelah kuliah?",
            opts: [["Sangat Tenang","Tidak khawatir"],["Tenang","Sedikit khawatir"],["Cukup Cemas","Kadang terpikir"],["Cemas","Sering muncul"],["Sangat Cemas","Selalu menghantui"]]
        }
    ];

    const agreementOptions = [["Sangat Tidak Setuju","Tidak sesuai"],["Tidak Setuju","Kurang sesuai"],["Netral","Cukup sesuai"],["Setuju","Sesuai"],["Sangat Setuju","Sangat sesuai"]];

    const questions = [
        // EXTRAVERSION (4 items) - Konteks TI/Informatika
        {
            cat: "EXTRAVERSION",
            catColor: "#EAB308",
            title: "Saya aktif berbagi ide dan presentasi dalam diskusi teknis di kelas atau meeting project.",
            desc: "IPIP-20 (Big Five) - Adapted for IT Students",
            opts: agreementOptions
        },
        {
            cat: "EXTRAVERSION",
            catColor: "#EAB308",
            title: "Saya lebih suka bekerja sendiri pada coding task daripada kolaborasi dalam tim.",
            desc: "IPIP-20 (Big Five) - Item reverse (R)",
            opts: agreementOptions
        },
        {
            cat: "EXTRAVERSION",
            catColor: "#EAB308",
            title: "Saya senang mengikuti komunitas tech, hackathon, atau seminar pemrograman.",
            desc: "IPIP-20 (Big Five) - Adapted for IT Students",
            opts: agreementOptions
        },
        {
            cat: "EXTRAVERSION",
            catColor: "#EAB308",
            title: "Saya tidak nyaman menjadi leader atau koordinator dalam project development.",
            desc: "IPIP-20 (Big Five) - Item reverse (R)",
            opts: agreementOptions
        },
        // AGREEABLENESS (4 items) - Konteks TI/Informatika
        {
            cat: "AGREEABLENESS",
            catColor: "#22C55E",
            title: "Saya mudah membantu teman yang kesulitan dengan debugging atau penjelasan konsep teknis.",
            desc: "IPIP-20 (Big Five) - Adapted for IT Students",
            opts: agreementOptions
        },
        {
            cat: "AGREEABLENESS",
            catColor: "#22C55E",
            title: "Saya tidak terlalu peduli jika teman saya menghadapi masalah dalam project atau assignment.",
            desc: "IPIP-20 (Big Five) - Item reverse (R)",
            opts: agreementOptions
        },
        {
            cat: "AGREEABLENESS",
            catColor: "#22C55E",
            title: "Saya berempati dan siap memberikan support ketika teman sedang struggle dengan deadline project.",
            desc: "IPIP-20 (Big Five) - Adapted for IT Students",
            opts: agreementOptions
        },
        {
            cat: "AGREEABLENESS",
            catColor: "#22C55E",
            title: "Saya tidak tertarik mendengarkan kesulitan atau masalah yang dihadapi rekan tim saya.",
            desc: "IPIP-20 (Big Five) - Item reverse (R)",
            opts: agreementOptions
        },
        // CONSCIENTIOUSNESS (4 items) - Konteks TI/Informatika
        {
            cat: "CONSCIENTIOUSNESS",
            catColor: "#EF4444",
            title: "Saya menyelesaikan assignment dan project dengan tepat waktu serta kualitas code yang baik.",
            desc: "IPIP-20 (Big Five) - Adapted for IT Students",
            opts: agreementOptions
        },
        {
            cat: "CONSCIENTIOUSNESS",
            catColor: "#EF4444",
            title: "Saya sering menunda-nunda pekerjaan coding hingga deadline terakhir (last minute).",
            desc: "IPIP-20 (Big Five) - Item reverse (R)",
            opts: agreementOptions
        },
        {
            cat: "CONSCIENTIOUSNESS",
            catColor: "#EF4444",
            title: "Saya terorganisir dalam mengelola file project, version control, dan dokumentasi code.",
            desc: "IPIP-20 (Big Five) - Adapted for IT Students",
            opts: agreementOptions
        },
        {
            cat: "CONSCIENTIOUSNESS",
            catColor: "#EF4444",
            title: "Saya sering berantakan dalam menyimpan, mendokumentasikan, dan mengelola project files.",
            desc: "IPIP-20 (Big Five) - Item reverse (R)",
            opts: agreementOptions
        },
        // NEUROTICISM (4 items) - Konteks TI/Informatika
        {
            cat: "NEUROTICISM",
            catColor: "#A855F7",
            title: "Saya mudah cemas dan stres menghadapi deadline project yang ketat atau exam teknis.",
            desc: "IPIP-20 (Big Five) - Adapted for IT Students",
            opts: agreementOptions
        },
        {
            cat: "NEUROTICISM",
            catColor: "#A855F7",
            title: "Saya tenang dan rileks meskipun sedang menghadapi bug kompleks atau masalah teknis yang sulit.",
            desc: "IPIP-20 (Big Five) - Item reverse (R)",
            opts: agreementOptions
        },
        {
            cat: "NEUROTICISM",
            catColor: "#A855F7",
            title: "Saya khawatir jika nilai atau performance saya dalam project tidak sesuai ekspektasi dosen.",
            desc: "IPIP-20 (Big Five) - Adapted for IT Students",
            opts: agreementOptions
        },
        {
            cat: "NEUROTICISM",
            catColor: "#A855F7",
            title: "Saya jarang merasa tertekan atau pesimis meski sedang mengerjakan project yang sangat kompleks.",
            desc: "IPIP-20 (Big Five) - Item reverse (R)",
            opts: agreementOptions
        },
        // OPENNESS (4 items) - Konteks TI/Informatika
        {
            cat: "OPENNESS",
            catColor: "#0EA5E9",
            title: "Saya tertarik belajar programming language dan teknologi baru yang sedang trending.",
            desc: "IPIP-20 (Big Five) - Adapted for IT Students",
            opts: agreementOptions
        },
        {
            cat: "OPENNESS",
            catColor: "#0EA5E9",
            title: "Saya lebih suka tetap menggunakan bahasa dan tools programming yang sudah familiar daripada mencoba yang baru.",
            desc: "IPIP-20 (Big Five) - Item reverse (R)",
            opts: agreementOptions
        },
        {
            cat: "OPENNESS",
            catColor: "#0EA5E9",
            title: "Saya memiliki imajinasi tinggi dalam mendesain algorithm, architecture, atau solusi sistem yang inovatif.",
            desc: "IPIP-20 (Big Five) - Adapted for IT Students",
            opts: agreementOptions
        },
        {
            cat: "OPENNESS",
            catColor: "#0EA5E9",
            title: "Saya cepat belajar dan memahami konsep teknis yang kompleks, baik dari dokumentasi maupun peer teaching.",
            desc: "IPIP-20 (Big Five) - Adapted for IT Students",
            opts: agreementOptions
        }
    ];

    const container = document.getElementById("questions");

    questions.forEach((q, index) => {
        let card = `
        <div class="card-question">
            <div class="q-top">
                <div class="q-num">${index + 1}</div>
                <div class="q-content">
                    <div class="category" style="color: ${q.catColor}">${q.cat}</div>
                    <div class="question-title">${q.title}</div>
                    <div class="desc">${q.desc}</div>
                </div>
            </div>
            <div class="scale-wrap">
                <div class="scale-line"></div>
                <div class="scale">
                    ${q.opts.map((op, i) => `
                        <label>
                            <input type="radio" name="jawaban[${index}]" value="${i + 1}" required onchange="updateProgress()">
                            <div class="circle ${colors[i]}"></div>
                            <div class="label-title">${op[0]}</div>
                            <div class="label-desc">${op[1]}</div>
                        </label>
                    `).join("")}
                </div>
            </div>
        </div>
        `;
        container.innerHTML += card;
    });
});

function updateProgress() {
    const total = 20;
    const checked = document.querySelectorAll('input[type=radio]:checked').length;
    const percent = (checked / total) * 100;

    document.getElementById("progressBar").style.width = percent + "%";
    document.getElementById("progressLabel").innerText = checked + " / " + total + " terjawab";

    const btn = document.getElementById("btnSubmit");

    if (checked === total) {
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
}
</script>

@endsection
