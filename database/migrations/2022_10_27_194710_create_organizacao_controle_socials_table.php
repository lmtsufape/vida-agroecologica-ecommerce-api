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
        Schema::create('organizacao_controle_socials', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('representante');
            $table->string('cnpj');
            $table->date('data_fundacao');
            $table->timestamps();

            $table->unsignedInteger('contato_id');
            $table->foreign('contato_id')->references('id')->on('contatos');
            $table->unsignedInteger('endereco_id');
            $table->foreign('endereco_id')->references('id')->on('enderecos');
            $table->unsignedInteger('associacao_id');
            $table->foreign('associacao_id')->references('id')->on('associacaos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizacao_controle_socials');
    }
};
