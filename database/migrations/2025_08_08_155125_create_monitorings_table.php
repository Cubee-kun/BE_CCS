<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('implementasi_id')->constrained();
            $table->integer('jumlah_bibit_ditanam');
            $table->integer('jumlah_bibit_mati');
            $table->decimal('diameter_batang', 5, 2);
            $table->integer('jumlah_daun');
            $table->json('survival_rate'); // Menyimpan data dalam format JSON
            $table->string('dokumentasi_monitoring_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitorings');
    }
};
