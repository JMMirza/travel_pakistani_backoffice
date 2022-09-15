<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandmarkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landmarks', function (Blueprint $table) {
            $table->id();     
            $table->unsignedBigInteger("categoryId");      
            $table->text("title");
            $table->string("phone")->nullable();
            $table->string("email",150)->nullable();
            $table->string("website")->nullable();
            $table->decimal("lat");
            $table->decimal("long");
            $table->unsignedBigInteger("cityId");
            $table->string("image")->nullable();            
            $table->tinyInteger("freeWifi")->nullable();            
            $table->string("ZoomLevel")->nullable();
            $table->double("rating")->nullable();
            $table->text("payment")->nullable();
            $table->text("tags")->nullable();
            $table->text("accessType")->nullable();
            $table->text("subCategories")->nullable();            
            $table->string("bestTimeToVisit")->nullable();
            $table->string("averageTimeOnSpot")->nullable();
            $table->tinyInteger("alcohal")->default("0");
            $table->text("services")->nullable();
            $table->text("seating")->nullable();
            $table->text("cuisines")->nullable();
            $table->text("menu")->nullable();
            $table->text("certification")->nullable();
            $table->tinyInteger("approved");   
            $table->unsignedBigInteger("masterId");         
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('landmarks', function (Blueprint $table) {            
            $table->foreign('categoryId')->references('id')->on('landmark_categories')->onUpdate("cascade")->onDelete("cascade");                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('landmarks');
    }
}
