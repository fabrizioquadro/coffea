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
        Schema::create('requisicao_anexos_gerais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisicao_id');
            $table->unsignedBigInteger('user_criacao_id');
            $table->string('link_anexo', 300);
            $table->timestamps();

            $table->foreign('requisicao_id')->references('id')->on('requisicaos');
            $table->foreign('user_criacao_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisicao_anexos_gerais');
    }
};
