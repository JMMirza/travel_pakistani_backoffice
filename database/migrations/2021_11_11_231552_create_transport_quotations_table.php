<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("quotationId");
            $table->string("description");            
            $table->string("serviceDate");
            $table->string("serviceType");   
            $table->string("instructions")->nullable();                                    
            $table->double("unitCost");
            $table->unsignedInteger("totalUnits");
            $table->double("markupValue")->nullable();
            $table->string("markupType")->nullable();
            $table->double("serviceCost");
            $table->double("serviceMarkupAmount")->default("0");
            $table->double("serviceSales");
            $table->unsignedBigInteger("versionNo")->default("1");
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::table('service_quotations', function (Blueprint $table) {            
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
        Schema::dropIfExists('service_quotations');
    }
}
