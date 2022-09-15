<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->string("companyTitle");
            $table->string("contactPerson");
            $table->string("businessEmail");
            $table->string("contactNumber");
            $table->string("companyAddress");
            $table->string("operatorLogo");
            $table->double("operatorRating");     
            $table->tinyInteger("status");            
            $table->string("operatorCode");
            $table->text("operatorAbout");     
            $table->tinyInteger("businessType");
            $table->softDeletes();
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
        Schema::dropIfExists('operators');
    }
}
