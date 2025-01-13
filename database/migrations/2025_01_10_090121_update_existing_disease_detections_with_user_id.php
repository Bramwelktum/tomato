<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class UpdateExistingDiseaseDetectionsWithUserId extends Migration
{
    public function up()
    {
        // Set a default user_id for existing records
        DB::table('disease_detections')->update(['user_id' => 1]);
    }

    public function down()
    {
        // Optionally, you can revert the changes in the down method
        DB::table('disease_detections')->update(['user_id' => null]);
    }
}
