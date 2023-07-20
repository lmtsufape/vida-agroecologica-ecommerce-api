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
        Schema::create('feiras', function (Blueprint $table) {
            $table->id();

            $table->json('funcionamento');
            $table->time('horario_abertura');
            $table->time('horario_fechamento');
            $table->foreignId('bairro_id')->constrained();
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
        Schema::dropIfExists('feiras');
    }
};
