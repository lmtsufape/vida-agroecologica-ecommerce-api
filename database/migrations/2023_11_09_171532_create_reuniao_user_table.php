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
        Schema::create('reuniao_user', function (Blueprint $table) {
            $table->id();

            $table->boolean('presenca')->default(false);

            $table->foreignId('reuniao_id')->constrained('reunioes')->cascadeOnDelete();
            $table->foreignId('participante_id')->constrained('users')->nullOnDelete();

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
        Schema::dropIfExists('reuniao_user');
    }
};
