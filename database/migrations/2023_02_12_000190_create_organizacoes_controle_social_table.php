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

            $table->string('nome', 60);
            $table->string('cnpj', 18)->unique();
            $table->date('data_fundacao');

            $table->foreignId('associacao_id')->constrained('associacoes')->restrictOnDelete();

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
        Schema::dropIfExists('organizacoes_controle_social');
    }
};
