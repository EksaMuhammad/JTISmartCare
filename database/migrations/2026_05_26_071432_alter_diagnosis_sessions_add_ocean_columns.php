<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diagnosis_sessions', function (Blueprint $table) {
            // Tambah kolom jawaban (belum ada sama sekali)
            $table->json('jawaban')->nullable();

            // Tambah kolom OCEAN + EDAS
            $table->json('aspek_psikologi')->nullable();
            $table->json('edas_result')->nullable();
            $table->integer('risk_index')->nullable();
            $table->text('rekomendasi_ai')->nullable();

            // Tambah kolom kategori_risiko (belum ada)
            $table->string('kategori_risiko')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('diagnosis_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'jawaban',
                'aspek_psikologi',
                'edas_result',
                'risk_index',
                'rekomendasi_ai',
                'kategori_risiko',
            ]);
        });
    }
};