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
        Schema::create('entrega_devolucaos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisicao_item_id');
            $table->unsignedBigInteger('user_criacao_id');
            $table->string('entrega_devolucao',1);
            $table->double('qtd',20,4);
            $table->text('justificativa')->nullable();
            $table->foreign('requisicao_item_id')->references('id')->on('requisicao_items');
            $table->foreign('user_criacao_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrega_devolucaos');
    }
};
