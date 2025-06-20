<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTripTable extends Migration
{
    public function up()
    {
        Schema::create('user_trip', function (Blueprint $table) {
            $table->id();

            // FK al usuario
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // FK al viaje
            $table->foreignId('trip_id')
                ->constrained()
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_trip');
    }
}
