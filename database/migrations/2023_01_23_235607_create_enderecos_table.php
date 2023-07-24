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
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->string('rua');
            $table->string('numero');
            $table->string('cep');
            $table->string('complemento')->nullable();
            $table->string('cidade');
            $table->string('estado');
            $table->string('pais');
            $table->string('bairro')->nullable();

            $table->foreignId('bairro_id')->constrained();
            $table->morphs('addressable');
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
        Schema::dropIfExists('enderecos');
    }
};
