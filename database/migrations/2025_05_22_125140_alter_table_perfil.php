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
        Schema::table('perfils', function($table){
            $table->unsignedBigInteger('user_id_cadastro')->nullable();
            $table->unsignedBigInteger('user_id_alteracao')->nullable();
            $table->foreign('user_id_cadastro')->references('id')->on('users');
            $table->foreign('user_id_alteracao')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
