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
        Schema::create('gps_trackers', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha');
            $table->decimal('latitud', 10, 6);
            $table->decimal('longitud', 10, 6);
            $table->foreignId('trip_id')->constrained('trips');
            $table->foreignId('local_storage_id')->constrained('local_storages')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gps_trackers');
    }
};
