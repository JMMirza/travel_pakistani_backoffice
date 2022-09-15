<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("userId");
            $table->string("title");
            $table->string("description");
            $table->string("templateType");
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('custom_templates', function (Blueprint $table) {            
            $table->foreign('userId')->references('id')->on('users');                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_templates');
    }
}
