<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asset_stocks', function (Blueprint $table) {
            $table->string('barcode')->nullable()->unique()->after('asset_id');
        });

        // Add Disposed to status enum in asset_stocks
        DB::statement("ALTER TABLE asset_stocks MODIFY COLUMN status ENUM('Available', 'Deployed', 'Maintenance', 'Broken', 'Lost', 'Disposed') DEFAULT 'Available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_stocks', function (Blueprint $table) {
            $table->dropColumn('barcode');
        });
        
        DB::statement("ALTER TABLE asset_stocks MODIFY COLUMN status ENUM('Available', 'Deployed', 'Maintenance', 'Broken', 'Lost') DEFAULT 'Available'");
    }
};
