<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItineraryTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itinerary_templates', function (Blueprint $table) {
            $table->id();            
            $table->string("templateTitle");              
            $table->unsignedBigInteger("categoryId");         
            $table->unsignedBigInteger("userId");
            $table->integer("totalDays"); 
            $table->tinyInteger("status");            
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('itinerary_templates', function (Blueprint $table) {            
            $table->foreign('categoryId')->references('id')->on('categories');                        
        });
        Schema::table('itinerary_templates', function (Blueprint $table) {            
            $table->foreign('userId')->references('id')->on('users');                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itinerary_templates');
    }
}
