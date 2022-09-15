<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_invoices', function (Blueprint $table) {
            $table->id();
            $table->integer("quotationId");
            $table->integer("quotationVersion");
            $table->date("invoiceDate");
            $table->string("pdf");
            $table->integer("dueAmount");
            $table->integer("totalAmount");
            $table->integer("remainingAmount");
            $table->integer("status")->default(0);
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
        Schema::dropIfExists('quotation_invoices');
    }
}
