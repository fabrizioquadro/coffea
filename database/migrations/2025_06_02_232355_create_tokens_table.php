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
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisicao_id')->unique();
            $table->unsignedBigInteger('user_criacao_id');
            $table->unsignedBigInteger('user_ativacao_id')->nullable();
            $table->dateTime('vencimento');
            $table->string('verificador');
            $table->dateTime('ativacao')->nullable();
            $table->foreign('requisicao_id')->references('id')->on('requisicaos');
            $table->foreign('user_criacao_id')->references('id')->on('users');
            $table->foreign('user_ativacao_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
