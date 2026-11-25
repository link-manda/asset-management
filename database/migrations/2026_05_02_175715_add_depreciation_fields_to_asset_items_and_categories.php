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
        Schema::table('asset_items', function (Blueprint $table) {
            $table->decimal('residual_value', 15, 2)->default(0)->after('purchase_price');
            $table->integer('useful_life_months')->nullable()->after('residual_value');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->integer('default_useful_life_months')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_items', function (Blueprint $table) {
            $table->dropColumn(['residual_value', 'useful_life_months']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('default_useful_life_months');
        });
    }
};
