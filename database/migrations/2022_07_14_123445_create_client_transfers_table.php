<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("orderId");
            $table->string("description");
            $table->string("transferType");
            $table->string("ticketType");
            $table->string("transferDetails");                                       
            $table->datetime("pickupTime");            
            $table->unsignedInteger("adults");
            $table->unsignedInteger("children");
            $table->unsignedInteger("fullTickets")->nullable();                                                                       
            $table->unsignedInteger("halfTickets")->nullable();
            $table->double("lat")->nullable();
            $table->double("long")->nullable();                                                                       
            $table->double("totalCost");            
            $table->double("totalSales");
            $table->text("instructions")->nullable();             
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('client_transfers', function (Blueprint $table) {            
            $table->foreign('orderId')->references('id')->on('quotation_orders')->onUpdate("cascade")->onDelete("cascade");                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_transfers');
    }
}
