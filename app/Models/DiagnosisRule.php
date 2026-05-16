<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisRule extends Model
{
    protected $fillable = [
        'rule_code',
        'kondisi',
        'kondisi_json',
        'hasil_risiko',
        'certainty_factor',
        'rekomendasi',
        'bobot',
    ];

    protected $casts = [
        'kondisi_json' => 'array',
    ];
}