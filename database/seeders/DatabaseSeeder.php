<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiagnosisRule;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@burnout.ac.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Demo mahasiswa
        User::updateOrCreate(
            ['email' => 'budi@mahasiswa.ac.id'],
            [
                'name' => 'Budi Santoso',
                'nim' => '2021001001',
                'jurusan' => 'Teknik Informatika',
                'angkatan' => '2021',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]
        );

        $rules = [
            // ===== RISIKO TINGGI (High) =====
            [
                'rule_code' => 'R01',
                'kondisi' => 'Tingkat stres tinggi DAN skor depresi tinggi DAN skor kecemasan tinggi',
                'kondisi_json' => [
                    ['variabel' => 'stress_level', 'operator' => '==', 'nilai' => 'High'],
                    ['variabel' => 'depression_score', 'operator' => '>=', 'nilai' => 8],
                    ['variabel' => 'anxiety_score', 'operator' => '>=', 'nilai' => 8],
                ],
                'hasil_risiko' => 'Tinggi',
                'certainty_factor' => 0.95,
                'rekomendasi' => 'Kondisi Anda sangat mengkhawatirkan dengan tingkat depresi dan cemas yang tinggi. Segera konsultasikan dengan konselor atau psikolog.',
                'bobot' => 10,
            ],
            [
                'rule_code' => 'R02',
                'kondisi' => 'Kualitas tidur buruk DAN tekanan akademik sangat tinggi',
                'kondisi_json' => [
                    ['variabel' => 'sleep_quality', 'operator' => '==', 'nilai' => 'Poor'],
                    ['variabel' => 'academic_pressure_score', 'operator' => '>=', 'nilai' => 8],
                ],
                'hasil_risiko' => 'Tinggi',
                'certainty_factor' => 0.88,
                'rekomendasi' => 'Kurang tidur drastis akibat tekanan akademik dapat memicu burnout berat. Kurangi beban dan prioritas istirahat.',
                'bobot' => 9,
            ],
            [
                'rule_code' => 'R03',
                'kondisi' => 'Stres level tinggi DAN dukungan sosial rendah',
                'kondisi_json' => [
                    ['variabel' => 'stress_level', 'operator' => '==', 'nilai' => 'High'],
                    ['variabel' => 'social_support_score', 'operator' => '<=', 'nilai' => 3],
                ],
                'hasil_risiko' => 'Tinggi',
                'certainty_factor' => 0.90,
                'rekomendasi' => 'Anda mengalami stres tinggi tanpa adanya dukungan sosial yang cukup. Cobalah mencari lingkungan sosial yang positif.',
                'bobot' => 9,
            ],
            // ===== RISIKO SEDANG (Medium) =====
            [
                'rule_code' => 'R04',
                'kondisi' => 'Tekanan akademik tinggi secara moderat DAN stres level medium',
                'kondisi_json' => [
                    ['variabel' => 'academic_pressure_score', 'operator' => '>=', 'nilai' => 5],
                    ['variabel' => 'stress_level', 'operator' => '==', 'nilai' => 'Medium'],
                ],
                'hasil_risiko' => 'Sedang',
                'certainty_factor' => 0.70,
                'rekomendasi' => 'Anda berisiko burnout sedang akibat beban yang mulai berat. Sesuaikan manajemen waktu dan luangkan waktu untuk relaksasi.',
                'bobot' => 6,
            ],
            [
                'rule_code' => 'R05',
                'kondisi' => 'Keuangan bermasalah DAN kualitas tidur rata-rata',
                'kondisi_json' => [
                    ['variabel' => 'financial_stress_score', 'operator' => '>=', 'nilai' => 6],
                    ['variabel' => 'sleep_quality', 'operator' => '==', 'nilai' => 'Average'],
                ],
                'hasil_risiko' => 'Sedang',
                'certainty_factor' => 0.60,
                'rekomendasi' => 'Tekanan keuangan perlahan memengaruhi pola Anda. Pertimbangkan mencari sumber dukungan finansial atau beasiswa.',
                'bobot' => 5,
            ],
            // ===== RISIKO RENDAH (Low) =====
            [
                'rule_code' => 'R06',
                'kondisi' => 'Kualitas tidur baik DAN dukungan sosial tinggi',
                'kondisi_json' => [
                    ['variabel' => 'sleep_quality', 'operator' => '==', 'nilai' => 'Good'],
                    ['variabel' => 'social_support_score', 'operator' => '>=', 'nilai' => 7],
                ],
                'hasil_risiko' => 'Rendah',
                'certainty_factor' => 0.85,
                'rekomendasi' => 'Anda memiliki kualitas dukungan yang baik dan rutinitas tidur yang sehat. Pertahankan kebiasaan ini!',
                'bobot' => 2,
            ],
            [
                'rule_code' => 'R07',
                'kondisi' => 'Stres level rendah DAN skor depresi sangat rendah',
                'kondisi_json' => [
                    ['variabel' => 'stress_level', 'operator' => '==', 'nilai' => 'Low'],
                    ['variabel' => 'depression_score', 'operator' => '<=', 'nilai' => 2],
                ],
                'hasil_risiko' => 'Rendah',
                'certainty_factor' => 0.95,
                'rekomendasi' => 'Kondisi Anda sangat baik. Tetap jaga keseimbangan kehidupan akademik dan sosial.',
                'bobot' => 1,
            ],
        ];

        foreach ($rules as $rule) {
            DiagnosisRule::updateOrCreate(
                ['rule_code' => $rule['rule_code']],
                $rule
            );
        }

        // Artikel edukasi
        $articles = [
            [
                'judul' => 'Apa Itu Burnout? Kenali Tanda-tandanya',
                'slug' => 'apa-itu-burnout-kenali-tanda-tandanya',
                'konten' => 'Burnout adalah kondisi kelelahan fisik, emosional, dan mental yang disebabkan oleh stres berkepanjangan. Berbeda dengan stres biasa, burnout adalah kondisi kronis yang mempengaruhi seluruh aspek kehidupan. Tanda-tanda burnout meliputi: kelelahan ekstrem, sinisme terhadap pekerjaan/studi, penurunan produktivitas, perasaan tidak efektif, dan penarikan diri dari lingkungan sosial. Burnout pada mahasiswa sering dipicu oleh beban akademik yang berlebihan, kurang tidur, kurangnya dukungan sosial, dan tekanan dari berbagai pihak. Mengenali burnout sejak dini sangat penting untuk mencegah dampak yang lebih serius pada kesehatan mental dan akademik.',
                'kategori' => 'info',
                'published' => true,
                'author_id' => 1,
                'source_name' => 'World Psychiatry (Wiley Online Library)',
                'source_url' => 'https://onlinelibrary.wiley.com/doi/full/10.1002/wps.20311',
            ],
            [
                'judul' => '7 Cara Efektif Mencegah Burnout Mahasiswa',
                'slug' => '7-cara-efektif-mencegah-burnout-mahasiswa',
                'konten' => '1. Kelola Waktu dengan Baik: Gunakan teknik Pomodoro (25 menit fokus, 5 menit istirahat) untuk meningkatkan produktivitas tanpa kelelahan. 2. Prioritaskan Tidur: Tidur 7-8 jam per malam bukan kemewahan, melainkan kebutuhan untuk fungsi kognitif optimal. 3. Olahraga Teratur: 30 menit olahraga ringan per hari terbukti mengurangi stres and meningkatkan mood. 4. Jaga Koneksi Sosial: Luangkan waktu untuk bersosialisasi dengan teman dan keluarga. 5. Tetapkan Batasan: Belajar untuk mengatakan tidak pada komitmen berlebihan. 6. Praktikkan Mindfulness: Meditasi 10-15 menit per hari dapat mengurangi kecemasan secara signifikan. 7. Cari Bantuan Profesional: Tidak ada salahnya berkonsultasi dengan konselor atau psikolog kampus.',
                'kategori' => 'tips',
                'published' => true,
                'author_id' => 1,
                'source_name' => 'NIH / PubMed Central',
                'source_url' => 'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC8472814/',
            ],
            [
                'judul' => 'Manajemen Stres Akademik: Panduan Praktis',
                'slug' => 'manajemen-stres-akademik-panduan-praktis',
                'konten' => 'Stres akademik adalah bagian tak terpisahkan dari kehidupan mahasiswa. Namun, stres yang tidak dikelola dapat berkembang menjadi burnout. Berikut strategi manajemen stres yang terbukti efektif: Identifikasi Sumber Stres - Tuliskan semua hal yang membuat Anda stres dan kategorikan berdasarkan prioritas dan urgensi. Teknik Pernapasan - Latihan pernapasan 4-7-8 (hirup 4 detik, tahan 7 detik, hembuskan 8 detik) efektif menenangkan sistem saraf. Journaling - Menulis jurnal harian membantu memproses emosi dan mendapatkan perspektif baru. Study Group - Belajar bersama teman dapat meringankan beban dan meningkatkan pemahaman. Batas Digital - Batasi penggunaan media sosial terutama menjelang tidur untuk kualitas istirahat yang lebih baik.',
                'kategori' => 'tips',
                'published' => true,
                'author_id' => 1,
                'source_name' => 'NIH / PubMed Central',
                'source_url' => 'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC7491717/',
            ],
        ];

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }

        // Panggil seeder tambahan
        $this->call(ArticlePencegahanSeeder::class);
    }
}
