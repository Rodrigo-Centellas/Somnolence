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
     Schema::create('trips', function (Blueprint $table) {
    $table->id();
    $table->integer('distancia_recorrida')->nullable();
    $table->string('estado');
    $table->date('fecha_inicio');
    $table->date('fecha_fin')->nullable();
    $table->foreignId('vehicle_id')->constrained('vehicles');
    $table->foreignId('route_id')->constrained('routes');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
