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
        Schema::create('bancas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome');
            $table->string('descricao');
            $table->time('horario_funcionamento');
            $table->time('horario_fechamento');
            $table->boolean('funcionamento')->default(false);
            $table->float('preco_minimo');
            $table->boolean('faz_entrega');
            $table->foreignId('feira_id')->default(1)->constrained('feiras');
            $table->foreignId('produtor_id')->constrained('produtores')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bancas');
    }
};
