<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_translations', function (Blueprint $table) {
            $table->id();                
            $table->foreignId('tourId')->constrained("tours");        
            $table->string("title")->nullable();
            $table->tinyText("shortDescription")->nullable();
            $table->text("details")->nullable();
            $table->string("metaTitle")->nullable();
            $table->tinyText("metaDescription")->nullable();
            $table->tinyText("metaKeywords")->nullable();              
            $table->foreignId('languageId')->constrained("system_languages");          
            $table->timestamps();
            $table->softDeletes();
        });
       /* Schema::table('tour_translations', function($table)
        {
            
            $table->foreign("tourId")->references('id')->on('tours')->onUpdate("cascade")->onDelete("cascade");
            $table->foreign("languageId")->references('id')->on('system_languages')->onUpdate("cascade")->onDelete("cascade");
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropForeign("tourId");
        Schema::dropForeign("languageId");
        Schema::dropIfExists('tour_translations');
    }
}
