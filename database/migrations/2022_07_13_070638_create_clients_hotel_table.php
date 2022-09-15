<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsHotelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("orderId");
            $table->unsignedBigInteger("hotelId");
            $table->string("roomNo");
            $table->string("adults"); 
            $table->string("children");
            $table->string("sharingType");
            $table->datetime("checkIn");
            $table->datetime("checkOut");
            $table->integer("nights");
            $table->double("price");
            $table->text("remarks");
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('client_hotels', function (Blueprint $table) {            
            $table->foreign('orderId')->references('id')->on('quotation_orders')->onUpdate("cascade")->onDelete("cascade");                        
        });
        Schema::table('client_hotels', function (Blueprint $table) {            
            $table->foreign('hotelId')->references('id')->on('hotels')->onUpdate("cascade")->onDelete("cascade");                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_hotels');
    }
}
