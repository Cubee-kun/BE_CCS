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
        Schema::create('implementasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perencanaan_id')->constrained();
            $table->boolean('nama_perusahaan_sesuai')->default(false);
            $table->boolean('lokasi_sesuai')->default(false);
            $table->boolean('jenis_kegiatan_sesuai')->default(false);
            $table->boolean('jumlah_bibit_sesuai')->default(false);
            $table->boolean('jenis_bibit_sesuai')->default(false);
            $table->boolean('tanggal_sesuai')->default(false);
            $table->string('pic_koorlap');
            $table->string('dokumentasi_kegiatan_path');
            $table->string('geotagging_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('implementasis');
    }
};
