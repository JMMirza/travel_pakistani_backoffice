<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotation_invoices', function (Blueprint $table) {
            $table->string("description")->after("id");
            $table->date("dueDate")->after("remainingAmount")->nullable();
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
        Schema::table('quotation_invoices', function (Blueprint $table) {
            $table->dropColumn("description");
            $table->dropColumn("dueDate");
            $table->dropSoftDeletes();
        });
    }
}
