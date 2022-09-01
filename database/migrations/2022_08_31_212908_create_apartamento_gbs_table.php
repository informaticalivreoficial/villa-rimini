<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApartamentoGbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartamento_gbs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('apartamento');
            $table->string('path');
            $table->boolean('cover')->nullable();
            $table->boolean('marcadagua')->nullable();
            $table->timestamps();

            $table->foreign('apartamento')->references('id')->on('apartamentos')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apartamento_gbs');
    }
}
