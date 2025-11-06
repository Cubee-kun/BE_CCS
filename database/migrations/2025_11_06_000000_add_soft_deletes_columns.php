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
        // Add deleted_at to perencanaans table
        if (Schema::hasTable('perencanaans') && !Schema::hasColumn('perencanaans', 'deleted_at')) {
            Schema::table('perencanaans', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to implementasis table
        if (Schema::hasTable('implementasis') && !Schema::hasColumn('implementasis', 'deleted_at')) {
            Schema::table('implementasis', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to monitorings table
        if (Schema::hasTable('monitorings') && !Schema::hasColumn('monitorings', 'deleted_at')) {
            Schema::table('monitorings', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to evaluasis table
        if (Schema::hasTable('evaluasis') && !Schema::hasColumn('evaluasis', 'deleted_at')) {
            Schema::table('evaluasis', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to dokumentasis table
        if (Schema::hasTable('dokumentasis') && !Schema::hasColumn('dokumentasis', 'deleted_at')) {
            Schema::table('dokumentasis', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('perencanaans') && Schema::hasColumn('perencanaans', 'deleted_at')) {
            Schema::table('perencanaans', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('implementasis') && Schema::hasColumn('implementasis', 'deleted_at')) {
            Schema::table('implementasis', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('monitorings') && Schema::hasColumn('monitorings', 'deleted_at')) {
            Schema::table('monitorings', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('evaluasis') && Schema::hasColumn('evaluasis', 'deleted_at')) {
            Schema::table('evaluasis', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('dokumentasis') && Schema::hasColumn('dokumentasis', 'deleted_at')) {
            Schema::table('dokumentasis', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
