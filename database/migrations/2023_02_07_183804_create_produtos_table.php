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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('descricao');
            $table->string('tipo_unidade');
            $table->integer('estoque');
            $table->decimal('preco');
            $table->decimal('custo');
            $table->boolean('à venda')->default(true);
            $table->foreignId('banca_id')->constrained('bancas')->onDelete('cascade');
            $table->foreignId('produto_tabelado_id')->constrained('produtos_tabelados')->onDelete('restrict');
            $table->unique(['banca_id', 'produto_tabelado_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
};
