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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('perfil_id');
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('login')->unique();
            $table->string('password');
            $table->string('imagem')->nullable();
            $table->foreign('perfil_id')->references('id')->on('perfils');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
