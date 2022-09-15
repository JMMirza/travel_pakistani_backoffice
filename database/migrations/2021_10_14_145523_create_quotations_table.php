<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("userId")->nullable();
            $table->unsignedBigInteger("inquiryId")->nullable();
            $table->string('clientName');
            $table->string('clientEmail')->nullable();
            $table->string('clientContact')->nullable();        
            $table->unsignedInteger('cityId');
            $table->json('citiesToVisit')->nullable();
            $table->json('otherAreas')->nullable();
            $table->date('tourFrom');
            $table->date('tourEnd');
            $table->unsignedInteger('adults')->default(0);
            $table->unsignedInteger('children')->default(0);
            $table->json('requiredServices');
            $table->date('validity');
            $table->string("status")->default("Draft");
            $table->string("reason")->nullable();
            $table->integer("masterId")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('quotations', function (Blueprint $table) {
            $table->foreign('userId')->references('id')->on('users');
            $table->foreign('inquiryId')->references('id')->on('inquiries');
        });   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
