<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalStatusFieldToToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {                        
            $table->string("approvalStatus")->default("Pending")->after("guideLanguages");
            $table->tinyText("changeInstructions")->nullable()->after("guideLanguages");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropIfExists("approvalStatus");
            $table->dropIfExists("changeInstructions");
        });
    }
}
