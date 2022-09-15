<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_credentials', function (Blueprint $table) {
            $table->id();
            $table->string("email");
            $table->string("phoneNumber");
            $table->string("title");
            $table->string("logo");
            $table->string("icon");
            $table->string("address");
            $table->string("about");
            $table->json("socialMediaLinks");
            $table->string("accountNo");
            $table->string("tagLine");
            $table->string("supportEmail");
            $table->string("supportPerson");
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
        Schema::dropIfExists('site_credentials');
    }
}
