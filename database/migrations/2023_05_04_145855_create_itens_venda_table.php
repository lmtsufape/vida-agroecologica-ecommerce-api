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
            $table->integer('quantidade')->nullable(false);
            $table->decimal('preco')->nullable(false);

            $table->foreignId('produto_id')->nullable(false)->constrained()->onDelete('restrict');
            $table->foreignId('venda_id')->nullable(false)->constrained();
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
