<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFatigueAlertTable extends Migration
{
    public function up()
    {
        Schema::create('fatigue_alert', function (Blueprint $table) {
            $table->id();
            // FK a alerts
            $table->foreignId('alert_id')
                  ->constrained()
                  ->onDelete('cascade');
            // FK a fatigue_detections
            $table->foreignId('fatigue_detection_id')
                  ->constrained()
                  ->onDelete('cascade');
            // columnas extra

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fatigue_alert');
    }
}
