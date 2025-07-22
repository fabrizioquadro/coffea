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
        Schema::create('setor_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('setor_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('setor_id')->references('id')->on('setors');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setor_user');
    }
};
