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
        Schema::create('ocs_agricultores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organizacoes_controle_social_id')->constrained('organizacoes_controle_social')->cascadeOnDelete();
            $table->foreignId('agricultor_id')->constrained('users')->restrictOnDelete();
            $table->unique(['organizacoes_controle_social_id', 'agricultor_id']);

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
        Schema::dropIfExists('ocs_agricultores');
    }
};
