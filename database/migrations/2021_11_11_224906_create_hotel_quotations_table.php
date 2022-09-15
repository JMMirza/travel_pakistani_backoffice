<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("quotationId");
            $table->string("hotelName");
            $table->string("checkIn");
            $table->string("checkout");   
            $table->unsignedInteger("nights");
            $table->string("instructions")->nullable();                        
            $table->double("markupValue")->nullable();
            $table->string("markupType")->nullable();
            $table->double("unitCost");
            $table->unsignedInteger("totalUnits");
            $table->double("hotelCost");
            $table->double("hotelMarkupAmount")->default("0");
            $table->double("hotelSales");
            $table->unsignedBigInteger("versionNo")->default("1");
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('hotel_quotations', function (Blueprint $table) {            
            $table->foreign('quotationId')->references('id')->on('quotations');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_quotations');
    }
}
