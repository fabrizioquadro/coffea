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
        Schema::create('requisicao_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisicao_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('user_criacao_id');
            $table->unsignedBigInteger('user_alteracao_id')->nullable();
            $table->text('obs')->nullable();
            $table->string('ds_unidade')->nullable();
            $table->double('valor_unid',10,2)->nullable();
            $table->double('qtd_pedida',10,4);
            $table->date('data_previsao_entrega')->nullable();
            $table->double('qtd_entregue',10,4)->nullable();
            $table->double('qtd_devolucao',10,4)->nullable();
            $table->double('qtd_total',10,4);
            $table->double('valor_total_pedido',10,2)->nullable();
            $table->double('valor_total_entregue',10,2)->nullable();
            $table->string('status');
            $table->boolean('status_canc_devol')->default(false);
            $table->boolean('lancar_patrimonio')->default(false);
            $table->foreign('requisicao_id')->references('id')->on('requisicaos');
            $table->foreign('item_id')->references('id')->on('items');
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
        Schema::dropIfExists('requisicao_items');
    }
};
