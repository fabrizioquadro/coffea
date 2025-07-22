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
        Schema::create('fornecedors', function (Blueprint $table) {
            $table->id();
            $table->string('sisagil_id');
            $table->string('nome');
            $table->string('fantasia');
            $table->string('cpf_cnpj')->nullable();
            $table->string('endereco')->nullable();
            $table->string('numero')->nullable();
            $table->string('bairro')->nullable();
            $table->string('complemento')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('celular')->nullable();
            $table->boolean('enviar_whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('cep')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fornecedors');
    }
};
