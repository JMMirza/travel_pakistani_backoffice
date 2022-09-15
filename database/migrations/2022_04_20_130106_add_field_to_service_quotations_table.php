<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToServiceQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_quotations', function (Blueprint $table) {
            $table->string("serviceDateType")->after("description")->default("day");
            $table->date("serviceEndDate")->after("serviceDate")->nullable();
            $table->integer("totalDays")->after("serviceType")->default("0");
            $table->integer("calculateByDays")->after("serviceType")->default("0");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_quotations', function (Blueprint $table) {
            $table->dropColumn("serviceDateType");
            $table->dropColumn("serviceEndDate");
            $table->dropColumn("totalDays");
            $table->dropColumn("calculateByDays");
        });
    }
}
