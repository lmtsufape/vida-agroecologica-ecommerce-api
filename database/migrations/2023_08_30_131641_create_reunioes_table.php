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

            $table->string('titulo', 60);
            $table->text('pauta');
            $table->enum('status', ['Em analise', 'Recusada', 'Aprovada'])->default('Em analise');
            $table->dateTime('data');
            $table->enum('tipo', ['ordinaria', 'extraordinaria', 'multirao']);

            $table->foreignId('associacao_id')->constrained('associacoes');
            $table->foreignId('organizacao_id')->nullable()->constrained('organizacoes_controle_social')->nullOnDelete();
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
