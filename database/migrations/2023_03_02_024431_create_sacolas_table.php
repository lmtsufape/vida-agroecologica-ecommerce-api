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
        Schema::create('sacolas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->decimal('total');
            $table->foreignId('carrinho_id')->nullable(true)->constrained('carrinhos');
            $table->foreignId('loja_id')->nullable(false)->constrained('bancas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sacolas');
    }
};
