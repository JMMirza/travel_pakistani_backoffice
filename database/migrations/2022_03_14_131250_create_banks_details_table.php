<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("userId");
            $table->string("accountNo");
            $table->string("accountTitle");
            $table->string("IBAN")->nullable();
            $table->string("swiftCode")->nullable();
            $table->string("bankName");            
            $table->string("bankAddress")->nullable();
            $table->string("bankPhone")->nullable();            
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('banks_details', function (Blueprint $table) {            
            $table->foreign('userId')->references('id')->on('users');                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks_details');
    }
}
