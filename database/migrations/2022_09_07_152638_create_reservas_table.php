<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('apartamento');
            $table->unsignedInteger('cliente');
            $table->integer('status')->default('0');
            $table->integer('adultos')->default('0');
            $table->integer('criancas_0_5')->default('0');
            $table->date('checkin')->nullable();
            $table->date('checkout')->nullable();
            $table->string('codigo')->nullable();
            $table->decimal('valor_venda', 10, 2)->nullable();
            $table->text('notasadicionais')->nullable();

            $table->timestamps();

            $table->foreign('apartamento')->references('id')->on('apartamentos')->onDelete('CASCADE'); 
            $table->foreign('cliente')->references('id')->on('users')->onDelete('CASCADE'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
