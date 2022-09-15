<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultAssigneeFieldToInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger("isAvailable")->after("fcmToken")->default("0");
            $table->integer("defaultAssigneeInquiries")->after("fcmToken")->nullable();
            $table->datetime("lastLogin")->after("status")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("isAvailable");
            $table->dropColumn("defaultAssigneeInquiries");
            $table->dropColumn("lastLogin");
        });
    }
}
