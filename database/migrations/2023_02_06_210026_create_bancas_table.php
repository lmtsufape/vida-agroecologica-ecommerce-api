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
            $table->string('nome')->nullable(false);
            $table->string('descricao')->nullable(false);
            $table->time('horario_funcionamento')->nullable(false);
            $table->time('horario_fechamento')->nullable(false);
            $table->boolean('funcionamento')->default(false);
            $table->float('preco_minimo')->nullable(false);
            $table->enum('tipo_entrega', ['ENTREGA', 'RETIRADA'])->nullable(false);
            $table->foreignId('feira_id')->nullable(false)->constrained('feiras');
            $table->foreignId('produtor_id')->nullable(false)->constrained('produtors');
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
