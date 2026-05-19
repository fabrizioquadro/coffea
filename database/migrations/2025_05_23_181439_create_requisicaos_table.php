<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requisicaos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fornecedor_id')->nullable();
            $table->unsignedBigInteger('setor_id');
            $table->unsignedBigInteger('unidade_id');
            $table->unsignedBigInteger('user_moderador_id')->nullable();
            $table->unsignedBigInteger('user_liberador_id')->nullable();
            $table->unsignedBigInteger('user_criacao_id');
            $table->unsignedBigInteger('user_alteracao_id')->nullable();
            $table->boolean('simples_cotacao')->default(false);
            $table->text('motivo_pedido_compra')->nullable();
            $table->text('justificativa')->nullable();
            $table->double('subtotal_pedido',10,2)->nullable();
            $table->double('subtotal_entregue',10,2)->nullable();
            $table->double('frete',10,2)->nullable();
            $table->double('outras_despesas',10,2)->nullable();
            $table->double('desconto',10,2)->nullable();
            $table->double('acrescimo',10,2)->nullable();
            $table->double('total_pedido',10,2)->nullable();
            $table->double('total_entregue',10,2)->nullable();
            $table->double('qtd_itens_pedido',10,4);
            $table->double('qtd_itens_entregue',10,4)->nullable();
            $table->date('data_previa_conclusao')->nullable();
            $table->boolean('aceito_pelo_fornecedor')->default(false);
            $table->dateTime('data_manifestacao_fornecedor')->nullable();
            $table->boolean('integrado')->default(false);
            $table->dateTime('data_integracao')->nullable();
            $table->string('status');
            $table->string('status_canc_devol')->nullable();
            $table->text('mensagem')->nullable();
            $table->string('fornecedor_email')->nullable();
            $table->string('fornecedor_whatsapp')->nullable();
            $table->dateTime('dt_hr_envio_email_fornecedor')->nullable();
            $table->text('justificativa_cancelamento')->nullable();
            $table->boolean('sem_validacao')->nullable();
            $table->string('portador')->nullable();
            $table->foreign('fornecedor_id')->references('id')->on('fornecedors');
            $table->foreign('setor_id')->references('id')->on('setors');
            $table->foreign('unidade_id')->references('id')->on('unidades');
            $table->foreign('user_moderador_id')->references('id')->on('users');
            $table->foreign('user_liberador_id')->references('id')->on('users');
            $table->foreign('user_criacao_id')->references('id')->on('users');
            $table->foreign('user_alteracao_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisicaos');
    }
};
