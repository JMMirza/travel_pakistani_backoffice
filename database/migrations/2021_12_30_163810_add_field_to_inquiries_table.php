<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->unsignedBigInteger("inquiryFrom")->after("assignedTo")->nullable();
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->foreign('inquiryFrom')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropForeign('inquiries_inquiryfrom_foreign');
            $table->dropColumn("inquiryFrom");
        });
    }
}
