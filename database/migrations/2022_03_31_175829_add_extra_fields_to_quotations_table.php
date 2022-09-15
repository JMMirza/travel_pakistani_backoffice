<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) { 
            $table->string("markupTypeQuotation",15)->after("extraMarkup");           
            $table->string("discountType")->after("totalSales")->nullable();
            $table->decimal("discountValue")->after("totalSales")->default("0");
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
            $table->dropColumn("discountType");
            $table->dropColumn("discountValue");
        });
    }
}
