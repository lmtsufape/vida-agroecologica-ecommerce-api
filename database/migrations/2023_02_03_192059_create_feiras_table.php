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
        Schema::create('feiras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->double('latitude')->nullable(false);
            $table->double('longitude')->nullable(false);
            $table->json('funcionamento')->nullable(false);
            $table->date('horario_abertura')->nullable(false);
            $table->date('horario_fechamento')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feiras');
    }
};
