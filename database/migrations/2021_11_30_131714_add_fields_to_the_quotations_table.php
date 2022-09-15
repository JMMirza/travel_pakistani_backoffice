<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTheQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $notes = array("cancellationPolicy"=>false,"bookingNotes"=>false,"paymentTerms"=>false,"freeText"=>false);
            $notes=json_encode($notes);
            $table->string("versionNo")->after("id")->default("1");
            $table->string("userNotes")->after("requiredServices")->default($notes);
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
            $table->dropColumn("versionNo");
            $table->dropColumn("userNotes");
        });
    }
}
