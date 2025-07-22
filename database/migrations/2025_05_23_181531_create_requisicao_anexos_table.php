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
        Schema::create('requisicao_anexos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisicao_id');
            $table->unsignedBigInteger('fornecedor_id');
            $table->unsignedBigInteger('user_criacao_id');
            $table->unsignedBigInteger('user_alteracao_id')->nullable();
            $table->text('link_anexo');
            $table->foreign('requisicao_id')->references('id')->on('requisicaos');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedors');
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
        Schema::dropIfExists('requisicao_anexos');
    }
};
