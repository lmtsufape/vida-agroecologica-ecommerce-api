<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();

            $table->string('rua', 60);
            $table->string('numero', 4);
            $table->string('cep', 9);
            $table->string('complemento', 120)->nullable();
            $table->string('cidade', 60);
            $table->string('estado', 30);
            $table->string('pais', 30);
            $table->string('bairro', 60)->nullable();

            $table->foreignId('bairro_id')->constrained()->restrictOnDelete();
            $table->morphs('addressable');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enderecos');
    }
};
