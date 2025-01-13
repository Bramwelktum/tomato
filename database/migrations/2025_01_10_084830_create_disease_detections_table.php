<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseaseDetectionsTable extends Migration
{
    public function up()
    {
        Schema::create('disease_detections', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->string('disease_name');
            $table->text('description');
            $table->text('remedy');
            $table->text('other_recommendations');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('disease_detections');
    }
}