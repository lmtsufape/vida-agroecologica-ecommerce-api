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

            $table->string('status')->nullable(false);
            $table->dateTime('data_pedido')->nullable(false);
            $table->decimal('total')->nullable(false);
            $table->binary('comprovante_pagamento');

            $table->foreignId('forma_pagamento_id')->nullable(false)->constrained('formas_pagamento');
            $table->foreignId('consumidor_id')->nullable(false)->constrained('consumidores');
            $table->foreignId('produtor_id')->nullable(false)->constrained('produtores');

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
