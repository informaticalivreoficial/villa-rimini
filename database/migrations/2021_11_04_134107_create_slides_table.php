<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('subtitulo')->nullable();
            $table->string('botaolabel')->nullable();
            $table->string('imagem')->nullable();
            $table->text('content')->nullable();
            $table->string('link')->nullable();
            $table->integer('target')->nullable();
            $table->string('slug')->nullable();
            $table->string('categoria')->nullable();
            $table->date('expira')->nullable();
            $table->integer('status')->nullable();
            $table->integer('exibir_titulo')->nullable();
            
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
        Schema::dropIfExists('slides');
    }
}
