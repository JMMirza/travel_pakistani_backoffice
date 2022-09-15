<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeFieldOptionalInItineraryTemplateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itinerary_template_details', function (Blueprint $table) {
            $table->unsignedBigInteger("cityId")->nullable()->change();
            $table->time("pickupTime")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itinerary_template_details', function (Blueprint $table) {
            $table->unsignedBigInteger("cityId")->change();
            $table->time("pickupTime")->change();
        });
    }
}
