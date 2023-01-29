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
        Schema::create('produtors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('banco_id')->nullable();
            $table->foreign('banco_id')->references('id')->on('bancos');
            $table->integer('banca_id')->nullable();
            $table->foreign('banca_id')->references('id')->on('bancas');
            $table->integer('distancia_feira');
            $table->integer('distancia_semana');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtors');
    }
};
