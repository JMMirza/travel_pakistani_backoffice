<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn("masterId");
            $table->string("quotationsTitle")->after("inquiryId")->default("Quotation Title");
            $table->unsignedBigInteger("approvedVersionId")->nullable()->after("reason");
            $table->unsignedBigInteger("quotationParent")->nullable()->after("id");
            $table->double("totalSales")->after("reason")->default("0");
            $table->double("totalCost")->after("reason")->default("0");
        });
        Schema::table('quotations', function (Blueprint $table) {
            $table->foreign('approvedVersionId')->references('id')->on('quotations');
            $table->foreign('quotationParent')->references('id')->on('quotations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {

            $table->unsignedBigInteger("masterId");
            $table->dropColumn("quotationsTitle");
            $table->dropForeign('quotations_approvedVersionId_foreign');
            $table->dropForeign("approvedVersionId");
            $table->dropForeign('quotations_approvedVersionId_foreign');
            $table->dropForeign("quotationParent");
            $table->dropForeign("totalSales");
            $table->dropForeign("totalCost");
        });
    }
}
