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
        Schema::create('asset_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->string('item_code')->unique(); // Barcode/QR Code internal
            $table->string('serial_number')->nullable(); // SN Pabrik
            
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->enum('condition', ['Good', 'Fair', 'Poor', 'Broken'])->default('Good');
            $table->enum('status', ['Available', 'Deployed', 'Maintenance', 'Disposed', 'Lost'])->default('Available');
            
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_items');
    }
};
