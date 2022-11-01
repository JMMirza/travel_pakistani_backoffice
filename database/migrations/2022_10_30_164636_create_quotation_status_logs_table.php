<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_status_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("quotationId");
            $table->foreign('quotationId')->references('id')->on('quotations')->onUpdate("cascade")->onDelete("cascade");
            $table->unsignedBigInteger("userId");
            $table->foreign('userId')->references('id')->on('users')->onUpdate("cascade")->onDelete("cascade");
            $table->unsignedBigInteger("statusId");
            $table->foreign('statusId')->references('id')->on('quotation_statuses')->onUpdate("cascade")->onDelete("cascade");
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
        Schema::dropIfExists('quotation_status_logs');
    }
};
