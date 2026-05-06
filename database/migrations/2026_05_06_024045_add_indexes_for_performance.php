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
        Schema::table('assets', function (Blueprint $table) {
            $table->index('name');
            $table->index('brand');
        });

        Schema::table('asset_items', function (Blueprint $table) {
            $table->index('serial_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['brand']);
        });

        Schema::table('asset_items', function (Blueprint $table) {
            $table->dropIndex(['serial_number']);
        });
    }
};
