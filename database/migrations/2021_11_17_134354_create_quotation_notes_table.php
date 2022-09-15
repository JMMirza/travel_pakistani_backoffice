<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("quotationId");
            $table->string("title");
            $table->text("description");
            $table->string("type");
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('quotation_notes', function (Blueprint $table) {            
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
        Schema::dropIfExists('quotation_notes');
    }
}
