<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class GeminiAIHelper
{
    /**
     * Get AI recommendation from Gemini API based on diagnosis result.
     * Recommendations are personalized based on individual OCEAN dimension scores,
     * not just the risk category label, so two users in the same category
     * (e.g. RISIKO SEDANG) will receive different, tailored advice.
     *
     * @param array $data
     * @return string|null
     */
    public static function getRecommendation(array $data): ?string
    {
        try {
            $apiKey = config('services.gemini.api_key');
            if (empty($apiKey)) {
                \Log::warning('Gemini API Key is not configured in services.gemini.api_key');
                return null;
            }

            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;

            // ---- Ringkasan skor per dimensi OCEAN ----
            $aspek = $data['aspek_psikologi'] ?? [];
            $oceanLines = [];
            foreach ($aspek as $key => $a) {
                $label  = $a['label'] ?? $key;
                $persen = $a['persen'] ?? 0;
                $level  = $persen >= 70 ? 'TINGGI' : ($persen >= 40 ? 'SEDANG' : 'RENDAH');
                $kode   = $a['kode'] ?? strtoupper(substr($key, 0, 1));
                $oceanLines[] = "- {$label} ({$kode}): {$persen}% → Level {$level}";
            }
            $oceanText = implode("\n", $oceanLines);

            // ---- Identifikasi profil ekstrem (tinggi/rendah) dari tiap trait ----
            $traitKritis = [];
            foreach ($aspek as $key => $a) {
                $persen = $a['persen'] ?? 0;
                if ($key === 'neuroticism') {
                    if ($persen >= 60) {
                        $traitKritis[] = "Neuroticism TINGGI ({$persen}%) — sangat rentan terhadap kecemasan, stres, ketakutan akan kegagalan, dan tekanan deadline.";
                    } elseif ($persen <= 30) {
                        $traitKritis[] = "Neuroticism RENDAH ({$persen}%) — sangat tenang, namun mungkin terlalu santai hingga meremehkan risiko tenggat waktu.";
                    }
                }
                if ($key === 'conscientiousness') {
                    if ($persen >= 75) {
                        $traitKritis[] = "Conscientiousness TINGGI ({$persen}%) — sangat terorganisir dan perfeksionis, berisiko kelelahan ekstrim karena bekerja terlalu keras (workaholic).";
                    } elseif ($persen <= 45) {
                        $traitKritis[] = "Conscientiousness RENDAH ({$persen}%) — kesulitan dengan disiplin, manajemen waktu, prokrastinasi (menunda pekerjaan), dan keteraturan kerja.";
                    }
                }
                if ($key === 'extraversion') {
                    if ($persen >= 75) {
                        $traitKritis[] = "Extraversion TINGGI ({$persen}%) — sangat butuh interaksi sosial, rentan over-commit ke banyak kegiatan/komunitas, mudah jenuh jika coding sendiri.";
                    } elseif ($persen <= 35) {
                        $traitKritis[] = "Extraversion RENDAH ({$persen}%) — cenderung menarik diri, kurang membangun koneksi sosial pendukung, rentan terisolasi saat mengalami kesulitan teknis/bug.";
                    }
                }
                if ($key === 'agreeableness') {
                    if ($persen >= 75) {
                        $traitKritis[] = "Agreeableness TINGGI ({$persen}%) — cenderung 'people pleaser', sulit berkata tidak saat dimintai tolong, sehingga sering memikul beban project orang lain.";
                    } elseif ($persen <= 35) {
                        $traitKritis[] = "Agreeableness RENDAH ({$persen}%) — skeptis, kritis, dan sangat kompetitif, berpotensi mengalami konflik interpersonal atau kurangnya support system di dalam tim.";
                    }
                }
                if ($key === 'openness') {
                    if ($persen >= 75) {
                        $traitKritis[] = "Openness TINGGI ({$persen}%) — terlalu banyak ide, berisiko 'shiny object syndrome' (ingin mencoba semua bahasa pemrograman baru tanpa menguasai dasar/fokus).";
                    } elseif ($persen <= 35) {
                        $traitKritis[] = "Openness RENDAH ({$persen}%) — kurang fleksibel terhadap perubahan teknologi, resisten untuk keluar dari zona nyaman, yang bisa menyebabkan kebuntuan karir/akademik.";
                    }
                }
            }
            $traitKritisText = empty($traitKritis)
                ? "Tidak ada trait yang berada di level ekstrem (semua dimensi berada dalam rentang moderat/seimbang)."
                : implode("\n", array_map(fn ($t) => "- {$t}", $traitKritis));

            $kategori  = $data['kategori_risiko'] ?? 'RISIKO SEDANG';
            $totalSkor = $data['total_skor'] ?? 0;
            $dataJson  = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            // ---- Bangun prompt yang personal & detail ----
            $prompt = "Anda adalah asisten Explainable AI (XAI) di bidang psikologi klinis dan akademik untuk mahasiswa Teknik Informatika/Ilmu Komputer.\n\n"
                . "Berikut adalah DATA DIAGNOSIS LENGKAP dari seorang mahasiswa:\n\n"
                . "**Kategori Risiko Burnout:** {$kategori}\n"
                . "**Indeks Risiko:** {$totalSkor}/100\n\n"
                . "**Profil Kepribadian OCEAN (skor aktual dari kuesioner IPIP-20):**\n"
                . "{$oceanText}\n\n"
                . "**Trait/Dimensi yang paling bermasalah pada mahasiswa ini:**\n"
                . "{$traitKritisText}\n\n"
                . "**Data lengkap untuk referensi:**\n"
                . "```json\n{$dataJson}\n```\n\n"
                . "---\n\n"
                . "PENTING: Rekomendasi Anda harus BENAR-BENAR PERSONAL dan SPESIFIK berdasarkan profil OCEAN mahasiswa ini.\n"
                . "Jangan berikan rekomendasi generik untuk kategori \"{$kategori}\" saja.\n"
                . "Dua orang dengan kategori risiko yang sama bisa mendapat rekomendasi yang SANGAT BERBEDA tergantung trait dominan mereka.\n"
                . "Selalu sebutkan nama trait/dimensi OCEAN yang relevan dan angka persentasenya.\n\n"
                . "Format output Anda harus menggunakan struktur Markdown berikut secara rapi:\n\n"
                . "## 🔍 Analisis Transparansi Diagnosis\n"
                . "Jelaskan secara ilmiah dan transparan (Explainable AI) mengapa mahasiswa ini didiagnosis **{$kategori}**.\n"
                . "Fokus pada trait OCEAN mana yang paling dominan mempengaruhi diagnosis (berdasarkan data aktual di atas, bukan generalisasi).\n"
                . "Sebutkan angka persentase aktualnya.\n\n"
                . "## ⚡ Faktor Pemicu Utama\n"
                . "Sebutkan 2-3 faktor psikologis SPESIFIK dari profil OCEAN mahasiswa ini yang paling berisiko memicu burnout.\n"
                . "Gunakan data skor aktual di atas, bukan asumsi umum.\n\n"
                . "## ✅ Rekomendasi Personalisasi\n"
                . "Berikan **3 langkah praktis yang UNIK dan SPESIFIK** untuk mahasiswa dengan profil kepribadian ini.\n"
                . "Setiap langkah harus:\n"
                . "- Merujuk langsung pada trait OCEAN yang bermasalah (sebutkan nama trait dan persentasenya)\n"
                . "- Dapat langsung dipraktikkan sebagai mahasiswa TI/Informatika\n"
                . "- Berbeda dari rekomendasi umum untuk kategori risiko \"{$kategori}\"\n\n"
                . "Gunakan bahasa Indonesia yang empatik, profesional, dan mudah dipahami mahasiswa.";

            $response = Http::withoutVerifying()->timeout(20)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
            } else {
                \Log::error('Gemini API Call Failed! Status: ' . $response->status() . ' Response: ' . $response->body());
            }
        } catch (\Exception $e) {
            \Log::error('Gemini API Exception: ' . $e->getMessage());
        }
        return null;
    }
}
