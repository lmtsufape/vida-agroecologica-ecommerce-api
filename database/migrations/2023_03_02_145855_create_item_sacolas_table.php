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
        Schema::create('item_sacolas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('quantidade');
            $table->decimal('preço');
            $table->foreignId('produto_id')->nullable(true)->constrained('produtos');
            $table->foreignId('sacola_id')->nullable(true)->constrained('sacolas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_sacolas');
    }
};
