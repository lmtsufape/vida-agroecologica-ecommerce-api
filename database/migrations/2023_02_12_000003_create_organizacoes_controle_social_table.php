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
        Schema::create('organizacoes_controle_social', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cnpj');
            $table->date('data_fundacao');
            $table->timestamps();

            $table->foreignId('associacao_id')->constrained('associacoes');
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
