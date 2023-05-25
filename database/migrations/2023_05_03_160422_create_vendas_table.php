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
            $table->dateTime('data_pedido');
            $table->decimal('total')->default(0);
            $table->binary('comprovante_pagamento')->nullable();

            $table->foreignId('forma_pagamento_id')->constrained('formas_pagamento');
            $table->foreignId('consumidor_id')->constrained('consumidores');
            $table->foreignId('produtor_id')->constrained('produtores');

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
