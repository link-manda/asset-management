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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('fiscal_group')->nullable()->after('default_residual_percentage');
        });

        Schema::table('asset_items', function (Blueprint $table) {
            $table->string('fiscal_group')->nullable()->after('useful_life_months');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('fiscal_group');
        });

        Schema::table('asset_items', function (Blueprint $table) {
            $table->dropColumn('fiscal_group');
        });
    }
};
