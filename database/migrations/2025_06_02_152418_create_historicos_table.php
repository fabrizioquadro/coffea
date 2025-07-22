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
        Schema::create('historicos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisicao_id');
            $table->unsignedBigInteger('user_id');
            $table->date('dt_historico');
            $table->text('ds_historico');
            $table->string('status');
            $table->foreign('requisicao_id')->references('id')->on('requisicaos');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historicos');
    }
};
