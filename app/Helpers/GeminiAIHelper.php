<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class GeminiAIHelper
{
    /**
     * Get AI recommendation from Gemini API based on diagnosis result.
     *
     * @param array $data
     * @return string|null
     */
    public static function getRecommendation(array $data): ?string
    {
        try {
            $apiKey = env('GEMINI_API_KEY');
            if (empty($apiKey)) {
                return null;
            }
            
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . $apiKey;

            $prompt = "Anda adalah asisten Explainable AI (XAI) di bidang psikologi klinis dan akademik. 
Tugas Anda adalah menganalisis data kepribadian OCEAN dan perhitungan model SPK EDAS berikut:
" . json_encode($data) . "

Berikan penjelasan diagnosis yang transparan dan rekomendasi klinis/akademis yang terstruktur dalam bahasa Indonesia yang empati, profesional, dan mudah dipahami oleh mahasiswa.

Format output Anda harus menggunakan struktur Markdown berikut secara rapi:
1. **Analisis Transparansi Diagnosis (Explainable AI)**: Jelaskan secara ilmiah mengapa mahasiswa didiagnosis dengan tingkat risiko tersebut (rendah/sedang/tinggi) berdasarkan kemiripan skor OCEAN mereka dengan target pakar di perhitungan EDAS. Sebutkan kriteria/trait mana yang paling dominan berkontribusi pada diagnosis tersebut (misal Neuroticism tinggi atau Conscientiousness rendah).
2. **Faktor Pemicu Utama (Key Triggers)**: Detail 1-2 faktor psikologis utama dari hasil tes mereka yang paling memicu kerentanan terhadap burnout.
3. **Rekomendasi Tindakan Personalisasi (Actionable Advice)**: Berikan 3 langkah praktis spesifik (misal teknik manajemen stres, penyesuaian belajar, atau istirahat) yang paling cocok dengan tipe kepribadian OCEAN mereka untuk menekan risiko burnout.";

            $response = Http::timeout(5)->post($url, [
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
            }
        } catch (\Exception $e) {
            \Log::error('Gemini API Error: ' . $e->getMessage());
        }
        return null;
    }
}
