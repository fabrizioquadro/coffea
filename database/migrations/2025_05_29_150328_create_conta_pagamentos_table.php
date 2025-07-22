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
        Schema::create('conta_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->integer('sisagil_id');
            $table->integer('unidade_id');
            $table->string('descricao');
            $table->string('cred_deb',1);
            $table->boolean('padrao');
            $table->foreign('unidade_id')->references('id')->on('unidades');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conta_pagamentos');
    }
};
