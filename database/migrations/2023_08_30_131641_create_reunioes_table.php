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
        Schema::create('reunioes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('detalhamento');
            $table->enum('status', ['Em analise', 'Recusada', 'Aprovada'])->default('Em analise');
            $table->date('data');
            $table->enum('tipo', ['Ordinaria', 'extraordinaria', 'multirão']);
            $table->foreignId('associacao_id')->constrained('associacoes');
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
        Schema::dropIfExists('reunioes');
    }
};
