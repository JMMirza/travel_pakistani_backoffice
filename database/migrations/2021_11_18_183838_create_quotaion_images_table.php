<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotaionImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_images', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("image");
            $table->unsignedBigInteger("quotationId");
            $table->unsignedInteger("version");
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('quotation_images', function (Blueprint $table) {            
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
        Schema::dropIfExists('quotation_images');
    }
}
