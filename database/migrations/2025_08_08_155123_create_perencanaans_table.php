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
        Schema::create('perencanaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('nama_perusahaan');
            $table->string('nama_pic');
            $table->string('narahubung');
            $table->enum('jenis_kegiatan', ['Planting Mangrove', 'Coral Transplanting']);
            $table->string('lokasi');
            $table->integer('jumlah_bibit');
            $table->string('jenis_bibit');
            $table->date('tanggal_pelaksanaan');
            $table->string('lat');
            $table->string('long');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perencanaans');
    }
};
