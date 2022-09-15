<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItineraryLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itinerary_locations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('orderId');
            $table->integer('orderNewId');
            $table->integer('sequence');
            $table->string('date');
            $table->double('log', 12,9);
            $table->double('lat', 12,9);
            $table->text('locationName');
            $table->integer('itineraryId');
            $table->integer('day');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itinerary_locations');
    }
}
