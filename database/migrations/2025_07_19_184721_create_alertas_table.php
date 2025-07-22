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
        Schema::create('alertas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('requisicao_id');
            $table->string('origem');
            $table->text('mensagem');
            $table->string('visualizacao');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('requisicao_id')->references('id')->on('requisicaos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas');
    }
};
