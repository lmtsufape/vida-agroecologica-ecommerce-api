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

            $table->string('nome', 60);
            $table->string('descricao', 120)->nullable();
            $table->time('horario_abertura');
            $table->time('horario_fechamento');
            $table->boolean('entrega');
            $table->float('preco_minimo')->default(0);

            $table->foreignId('feira_id')->default(1)->constrained()->restrictOnDelete();
            $table->foreignId('agricultor_id')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
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
