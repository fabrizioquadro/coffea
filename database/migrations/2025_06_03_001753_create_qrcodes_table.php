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
        Schema::create('qrcodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisicao_id');
            $table->text('link');
            $table->dateTime('vencimento');
            $table->string('ip_ultima_leitura')->nullable();
            $table->dateTime('data_ultima_leitura')->nullable();
            $table->string('tipo_validacao')->nullable();
            $table->string('justificativa')->nullable();
            $table->string('aceite_fornecedor')->nullable();
            $table->dateTime('manifestacao_fornecedor')->nullable();
            $table->foreign('requisicao_id')->references('id')->on('requisicaos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qrcodes');
    }
};
