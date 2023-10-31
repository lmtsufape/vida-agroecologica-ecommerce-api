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

            $table->string('name', 60);
            $table->string('descricao', 120);
            $table->json('horarios_funcionamento');

            $table->foreignId('bairro_id')->constrained()->restrictOnDelete();
            $table->foreignId('associacao_id')->constrained('associacoes')->cascadeOnDelete();

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
