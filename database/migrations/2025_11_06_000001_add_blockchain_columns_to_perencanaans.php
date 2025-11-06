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
        // Add blockchain columns to perencanaans table
        if (Schema::hasTable('perencanaans')) {
            Schema::table('perencanaans', function (Blueprint $table) {
                if (!Schema::hasColumn('perencanaans', 'blockchain_document_id')) {
                    $table->string('blockchain_document_id')->nullable()->after('long');
                }
                if (!Schema::hasColumn('perencanaans', 'blockchain_tx_hash')) {
                    $table->string('blockchain_tx_hash')->nullable()->after('blockchain_document_id');
                }
                if (!Schema::hasColumn('perencanaans', 'ipfs_cid')) {
                    $table->string('ipfs_cid')->nullable()->after('blockchain_tx_hash');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('perencanaans')) {
            Schema::table('perencanaans', function (Blueprint $table) {
                if (Schema::hasColumn('perencanaans', 'blockchain_document_id')) {
                    $table->dropColumn('blockchain_document_id');
                }
                if (Schema::hasColumn('perencanaans', 'blockchain_tx_hash')) {
                    $table->dropColumn('blockchain_tx_hash');
                }
                if (Schema::hasColumn('perencanaans', 'ipfs_cid')) {
                    $table->dropColumn('ipfs_cid');
                }
            });
        }
    }
};
