<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertGpsTable extends Migration
{
    public function up()
    {
        Schema::create('alert_gps', function (Blueprint $table) {
            $table->id();
            // FK a alerts
            $table->foreignId('alert_id')
                  ->constrained()
                  ->onDelete('cascade');
            // FK a gps_trackers
            $table->foreignId('gps_tracker_id')
                  ->constrained()
                  ->onDelete('cascade');
            // columnas extra
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alert_gps');
    }
}
