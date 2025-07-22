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
        Schema::create('financeiros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisicao_id');
            $table->unsignedBigInteger('fornecedor_id');
            $table->unsignedBigInteger('operacao_id');
            $table->unsignedBigInteger('conta_pagamento_id');
            $table->unsignedBigInteger('user_criacao_id');
            $table->unsignedBigInteger('user_alteracao_id')->nullable();
            $table->string('cred_deb');
            $table->string('tipo_pagamento');
            $table->string('origem');
            $table->string('descricao');
            $table->date('vencimento')->nullable();
            $table->double('valor',10,2);
            $table->string('doc')->nullable();
            $table->text('obs')->nullable();
            $table->string('sisagil_id_retorno')->nullable();
            $table->foreign('requisicao_id')->references('id')->on('requisicaos');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedors');
            $table->foreign('operacao_id')->references('id')->on('operacaos');
            $table->foreign('conta_pagamento_id')->references('id')->on('conta_pagamentos');
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
        Schema::dropIfExists('financeiros');
    }
};
