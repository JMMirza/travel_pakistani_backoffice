<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {            
            $table->string("logo")->after("siteId")->nullable();
            $table->string("contactPerson")->after("branchEmail")->nullable();
            $table->string("branchAddress")->after("branchEmail")->nullable();
            $table->string("branchPhone")->after("branchEmail")->nullable();
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
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn("contactPerson");
            $table->dropColumn("branchAddress");
            $table->dropColumn("branchPhone");
            $table->dropSoftDeletes();
        });
    }
}
