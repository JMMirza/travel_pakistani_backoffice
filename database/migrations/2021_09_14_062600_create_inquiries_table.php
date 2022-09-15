<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("inquiryMasterId")->nullable();
            $table->unsignedInteger('productId')->nullable();
            $table->unsignedInteger('assignedTo')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contactNo')->nullable();        
            $table->unsignedInteger('cityId');
            $table->json('citiesToVisit');
            $table->json('otherAreas');
            $table->date('tourFrom');
            $table->date('tourEnd');
            $table->unsignedInteger('adults');
            $table->unsignedInteger('children')->default(0);
            $table->json('requiredServices');
            $table->text('specialRequest');            
            $table->string('source',30)->default('B2C Website');            
            $table->text('staffRemarks')->nullable();            
            $table->string('status',30)->default('New');
            $table->string('reason',500)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiries');
    }
}
