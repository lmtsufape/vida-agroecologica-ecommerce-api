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
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();

            $table->string('status');
            $table->enum('tipo_entrega', ['retirada', 'entrega']);
            $table->decimal('subtotal')->default(0);
            $table->decimal('taxa_entrega')->default(0);
            $table->decimal('total')->default(0);
            $table->dateTime('data_pedido');
            $table->dateTime('data_confirmacao')->nullable();
            $table->dateTime('data_cancelamento')->nullable();
            $table->dateTime('data_pagamento')->nullable();
            $table->dateTime('data_envio')->nullable();
            $table->dateTime('data_entrega')->nullable();

            $table->foreignId('forma_pagamento_id')->constrained('formas_pagamento');
            $table->foreignId('consumidor_id')->constrained('users');
            $table->foreignId('produtor_id')->constrained('users');

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
        Schema::dropIfExists('vendas');
    }
};
