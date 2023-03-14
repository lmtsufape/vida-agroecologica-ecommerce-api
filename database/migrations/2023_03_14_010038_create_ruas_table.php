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
        Schema::create('ruas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('numero');
            $table->string('complemento')->nullable(true);
            $table->foreignId('bairro_id')->nullable(false)->constrained('bairros');
            $table->foreignId('cep_id')->nullable(false)->constrained('ceps');
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
        Schema::dropIfExists('ruas');
    }
};
