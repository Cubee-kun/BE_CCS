<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('implementasi_id')->constrained()->onDelete('cascade');
            $table->decimal('survival_rate', 5, 2);
            $table->decimal('tinggi_bibit_rata', 8, 2);
            $table->decimal('diameter_batang_rata', 8, 2);
            $table->string('kondisi_kesehatan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluasis');
    }
};