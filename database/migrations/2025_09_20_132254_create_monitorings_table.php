<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('implementasi_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah_bibit_ditanam');
            $table->integer('jumlah_bibit_mati');
            $table->decimal('diameter_batang', 8, 2);
            $table->integer('jumlah_daun');
            $table->enum('daun_mengering', ['<25%', '25–45%', '50–74%', '>75%']);
            $table->enum('daun_layu', ['<25%', '25–45%', '50–74%', '>75%']);
            $table->enum('daun_menguning', ['<25%', '25–45%', '50–74%', '>75%']);
            $table->enum('bercak_daun', ['<25%', '25–45%', '50–74%', '>75%']);
            $table->enum('daun_serangga', ['<25%', '25–45%', '50–74%', '>75%']);
            $table->decimal('survival_rate', 5, 2);
            $table->string('dokumentasi_monitoring')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('monitorings');
    }
};
