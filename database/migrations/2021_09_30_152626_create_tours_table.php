<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();             
            $table->unsignedBigInteger('categoryId');        
            $table->json("cities");
            $table->string("duration")->nullable();
            $table->string("activitySpan")->nullable();
            $table->string("videoUrl")->nullable();
            $table->string("audioUrl")->nullable();
            $table->string("guideLanguages")->nullable();
            $table->unsignedInteger("tourMasterId");
            $table->tinyInteger("status")->default('0');
            $table->timestamps();
            $table->softDeletes();
        });    
        Schema::table('tours', function (Blueprint $table) {
            $table->foreign('categoryId')->references('id')->on('categories');
        });              
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropForeign("categoryId");
        Schema::dropIfExists('tours');
    }
}
