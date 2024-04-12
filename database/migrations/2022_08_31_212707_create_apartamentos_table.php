<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApartamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('slug')->nullable();
            $table->integer('status')->default('0');
            $table->string('headline')->nullable();
            $table->text('metatags')->nullable();
            $table->text('descricao')->nullable();            
            $table->text('notasadicionais')->nullable();
            $table->boolean('exibirmarcadagua')->nullable();
            $table->text('youtube_video')->nullable();             
            $table->bigInteger('views')->default('0');

            /** pricing and values */
            $table->boolean('exibir_valores')->nullable();
            $table->boolean('exibir_home')->nullable();
            $table->decimal('valor_venda', 10, 2)->nullable();
            $table->integer('dormitorios')->default('0');

            $table->decimal('valor_cafe', 10, 2)->nullable();
            $table->decimal('valor_cafe_almoco', 10, 2)->nullable();
            $table->decimal('valor_cafe_janta', 10, 2)->nullable();
            $table->decimal('valor_cri_0_5', 10, 2)->nullable();

            $table->boolean('ar_condicionado')->nullable();
            $table->boolean('cafe_manha')->nullable();
            $table->boolean('cofre_individual')->nullable();
            $table->boolean('frigobar')->nullable();
            $table->boolean('servico_quarto')->nullable();
            $table->boolean('telefone')->nullable();
            $table->boolean('estacionamento')->nullable();
            $table->boolean('espaco_fitness')->nullable();
            $table->boolean('lareira')->nullable();
            $table->boolean('elevador')->nullable();
            $table->boolean('vista_para_mar')->nullable();
            $table->boolean('ventilador_teto')->nullable();
            $table->boolean('wifi')->nullable();

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
        Schema::dropIfExists('apartamentos');
    }
}
