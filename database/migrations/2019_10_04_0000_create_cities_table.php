<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('city_code')->nullable();
            $table->string('title');
            $table->unsignedBigInteger('fk_branch_id')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('sort_order')->nullable();
            $table->string('is_active')->nullable();
            $table->string('is_deleted')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->dateTime('dated')->nullable();
            $table->dateTime('last_updated')->nullable();
            $table->string('city_emergency_number')->nullable();
            $table->text('city_introduction')->nullable();
            $table->string('city_image')->nullable();
            $table->tinyInteger('show_in_app')->nullable();
            $table->decimal('city_lat')->nullable();
            $table->decimal('city_long')->nullable();
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
        Schema::dropIfExists('cities');
    }
};
