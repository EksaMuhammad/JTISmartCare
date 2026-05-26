<?php

// ============================================================
// STEP 2 - MODEL
// FILE: app/Models/DiagnosisSession.php
// CARA: Buat file baru di app/Models/DiagnosisSession.php
// Lalu isi dengan kode di bawah ini
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosisSession extends Model
{
    use HasFactory;

    protected $table = 'diagnosis_sessions';

    protected $fillable = [
        'user_id',
        'jawaban',
        'aspek_psikologi',
        'edas_result',
        'risk_index',
        'total_skor',
        'kategori_risiko',
        'rule_terpilih',
        'rekomendasi',
        'rekomendasi_ai',
    ];

    // Cast otomatis JSON ke array PHP
    protected $casts = [
        'jawaban'         => 'array',
        'aspek_psikologi' => 'array',
        'edas_result'     => 'array',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}