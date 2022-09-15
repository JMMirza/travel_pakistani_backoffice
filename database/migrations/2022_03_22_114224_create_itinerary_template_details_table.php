<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItineraryTemplateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itinerary_template_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("templateId");            
            $table->unsignedBigInteger("cityId");
            $table->integer("dayNo");
            $table->time("pickupTime");
            $table->longText("description");
            $table->timestamps();
            $table->softDeletes();
        });                
        Schema::table('itinerary_template_details', function (Blueprint $table) {            
            $table->foreign('templateId')->references('id')->on('itinerary_templates');                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itinerary_template_details');
    }
}
