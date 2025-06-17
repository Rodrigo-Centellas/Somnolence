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
Schema::create('routes', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->double('latitud_origen');
    $table->double('longitud_origen');
    $table->double('latitud_destino');
    $table->double('longitud_destino');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
