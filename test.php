<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "DiagnosisSessions Count: " . \App\Models\DiagnosisSession::count() . "\n";
echo "Diagnoses Count: " . \App\Models\Diagnosis::count() . "\n";

foreach (\App\Models\DiagnosisSession::latest()->take(3)->get() as $s) {
    echo "Session ID: {$s->id}, User ID: {$s->user_id}, Risk Index: {$s->risk_index}, Kategori: {$s->kategori_risiko}\n";
}

foreach (\App\Models\Diagnosis::latest()->take(3)->get() as $d) {
    echo "Diagnosis ID: {$d->id}, Nama: {$d->nama}, Tingkat: {$d->tingkat_burnout}, Hasil: {$d->hasil}\n";
}
