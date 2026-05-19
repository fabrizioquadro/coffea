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
        Schema::create('operacaos', function (Blueprint $table) {
            $table->id();
            $table->integer('sisagil_id');
            $table->string('descricao');
            $table->string('status');
            $table->string('operacao_padrao_cancelamento',5)->default('Não');
            $table->unsignedBigInteger('unidade_id')->nullable();
            $table->foreign('unidade_id')->references('id')->on('unidades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operacaos');
    }
};
