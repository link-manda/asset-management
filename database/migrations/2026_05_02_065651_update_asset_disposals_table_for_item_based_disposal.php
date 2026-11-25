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
        Schema::table('asset_disposals', function (Blueprint $table) {
            // Drop old foreign key and column
            $table->dropForeign(['asset_id']);
            $table->dropColumn('asset_id');

            // Add new foreign key to asset_stocks
            $table->foreignId('asset_stock_id')->after('id')->constrained('asset_stocks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_disposals', function (Blueprint $table) {
            $table->dropForeign(['asset_stock_id']);
            $table->dropColumn('asset_stock_id');
            $table->foreignId('asset_id')->after('id')->constrained()->onDelete('cascade');
        });
    }
};
