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

            $table->string('descricao', 120);
            $table->string('tipo_unidade', 30);
            $table->double('estoque', 6, 3);
            $table->decimal('preco');
            $table->decimal('custo')->nullable();
            $table->boolean('disponivel')->default(true);

            $table->foreignId('banca_id')->constrained('bancas')->cascadeOnDelete();
            $table->foreignId('produto_tabelado_id')->constrained('produtos_tabelados')->restrictOnDelete();
            $table->unique(['banca_id', 'produto_tabelado_id']);

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
        Schema::dropIfExists('produtos');
    }
};
