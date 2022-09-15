<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("userId");
            $table->unsignedBigInteger("quotationId")->nullable();
            $table->string("orderReference")->nullable();
            $table->string("clientName");
            $table->string("clientEmail");
            $table->string("clientContact");            
            $table->string("cityId");
            $table->string("citiesToVisit");
            $table->string("requiredServices");
            $table->date("tourFrom");
            $table->date("tourEnd");
            $table->integer("adults");
            $table->integer("children");
            $table->double("totalCost");
            $table->double("totalSales");
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('quotation_orders', function (Blueprint $table) {            
            $table->foreign('quotationId')->references('id')->on('quotations')->onUpdate("cascade")->onDelete("cascade");                        
        });
        Schema::table('quotation_orders', function (Blueprint $table) {            
            $table->foreign('userId')->references('id')->on('users')->onUpdate("cascade")->onDelete("cascade");                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotation_orders');
    }
}
