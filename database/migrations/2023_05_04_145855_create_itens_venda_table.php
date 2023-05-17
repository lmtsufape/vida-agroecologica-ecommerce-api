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
        Schema::create('itens_venda', function (Blueprint $table) {
            $table->id();

            $table->string('tipo_unidade');
            $table->integer('quantidade');
            $table->decimal('preco');

            $table->foreignId('produto_id')->constrained()->onDelete('restrict');
            $table->foreignId('venda_id')->constrained();
            $table->unique(['produto_id', 'venda_id']);

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
        Schema::dropIfExists('itens_venda');
    }
};
