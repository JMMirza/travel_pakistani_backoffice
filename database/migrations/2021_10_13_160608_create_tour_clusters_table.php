<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourClustersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_clusters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("tourId");
            $table->unsignedBigInteger("selectedCluster");
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('tour_clusters', function (Blueprint $table) {
            $table->foreign('tourId')->references('id')->on('tours');
            $table->foreign('selectedCluster')->references('id')->on('clusters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tour_clusters');
    }
}
