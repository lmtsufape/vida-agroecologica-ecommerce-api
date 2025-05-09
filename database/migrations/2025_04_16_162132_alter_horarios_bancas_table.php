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
        Schema::table('bancas', function (Blueprint $table) {
            $table->json('horarios_funcionamento')->nullable();
            $table->dropColumn('horario_abertura');
            $table->dropColumn('horario_fechamento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bancas', function (Blueprint $table) {
            $table->time('horario_abertura');
            $table->time('horario_fechamento');
            $table->dropColumn('horarios_funcionamento');
        });
    }
};
