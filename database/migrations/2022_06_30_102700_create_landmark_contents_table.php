<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandmarkContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landmark_contents', function (Blueprint $table) {
            $table->id();   
            $table->unsignedBigInteger("landmarkId");           
            $table->text("title");
            $table->text("address")->nullable();                        
            $table->text("longDesc")->nullable();            
            $table->text("experience");
            $table->text("history");
            $table->text("landmarkActivities");
            $table->text("notification");
            $table->text("audioLink")->nullable();
            $table->text("videoLink")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('landmark_contents', function (Blueprint $table) {            
            $table->foreign('landmarkId')->references('id')->on('landmarks')->onUpdate("cascade")->onDelete("cascade");                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('landmark_contents');
    }
}
