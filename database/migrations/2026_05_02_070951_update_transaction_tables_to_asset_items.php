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
        // 1. Update Asset Assignments
        Schema::table('asset_assignments', function (Blueprint $table) {
            $table->dropForeign(['asset_id']);
            $table->dropColumn('asset_id');
            $table->foreignId('asset_item_id')->after('id')->constrained('asset_items')->onDelete('cascade');
        });

        // 2. Update Asset Maintenances
        Schema::table('asset_maintenances', function (Blueprint $table) {
            $table->dropForeign(['asset_id']);
            $table->dropColumn('asset_id');
            $table->foreignId('asset_item_id')->after('id')->constrained('asset_items')->onDelete('cascade');
        });

        // 3. Update Asset Disposals (already pointing to asset_stock_id, change to asset_item_id)
        Schema::table('asset_disposals', function (Blueprint $table) {
            $table->dropForeign(['asset_stock_id']);
            $table->dropColumn('asset_stock_id');
            $table->foreignId('asset_item_id')->after('id')->constrained('asset_items')->onDelete('cascade');
        });

        // 4. Cleanup Assets Table (remove columns that are now at item level)
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn(['status', 'purchase_date', 'location_id']);
        });
        
        // 5. Drop old asset_stocks table
        Schema::dropIfExists('asset_stocks');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not implemented fully for simplicity in this major refactor
    }
};
