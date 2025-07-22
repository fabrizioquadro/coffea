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
        Schema::create('perfils', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->boolean('administrador');
            $table->boolean('criar');
            $table->boolean('preparar_compra');
            $table->boolean('duplicar_pedido_compra');
            $table->boolean('moderar');
            $table->boolean('aprovar');
            $table->boolean('confirmar_recebimento');
            $table->boolean('alterar_qtd_recebimento');
            $table->boolean('editar');
            $table->boolean('corrigir');
            $table->boolean('cancelar');
            $table->boolean('acompanhar');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfils');
    }
};
