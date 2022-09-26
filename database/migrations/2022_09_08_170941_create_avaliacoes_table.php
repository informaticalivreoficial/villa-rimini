<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            // 1. Quarto - Limpeza
            // 2. Quarto - Limpeza Banheiro.
            // 3. Quarto - Geral
            // 4. A equipe - Eficiência
            // 5. A equipe - Simpatia
            // 6. A equipe - Cortesia
            // 7. Estava tudo em bom funcionamento no seu quarto?
            // 7.1. Se colocou “Não”, explique o que precisava ser reparado.
            // 8. O responsável da manutenção foi eficiente?
            // 9. Ficaria novamente no hotel?
            // 10. Recomendaria o nosso hotel?
            // 11. Como efetou a sua reserva?
            // Excelente = 4
            // Bom = 3
            // Suficiente = 2
            // Mau = 1
            // Péssimo = 0
            $table->increments('id');
            $table->integer('status')->default('0');
            $table->string('name');
            $table->date('checkout')->nullable();
            $table->string('regiao')->nullable();
            $table->string('email')->nullable();
            $table->integer('uf')->nullable();
            $table->integer('cidade')->nullable();
            $table->integer('questao_1')->default('0');
            $table->integer('questao_2')->default('0');
            $table->integer('questao_3')->default('0');
            $table->integer('questao_4')->default('0');
            $table->integer('questao_5')->default('0');
            $table->integer('questao_6')->default('0');
            $table->integer('questao_7')->default('0');
            $table->text('questao_7_content')->nullable();
            $table->integer('questao_8')->default('0');
            $table->integer('questao_9')->default('0');
            $table->integer('questao_10')->default('0');
            $table->string('questao_11')->nullable();
            $table->text('content')->nullable();            

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
        Schema::dropIfExists('avaliacoes');
    }
}
