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
        Schema::create('banca_forma_pagamento', function (Blueprint $table) {
            $table->id();

            $table->foreignId('banca_id')->constrained()->cascadeOnDelete();
            $table->foreignId('forma_pagamento_id')->constrained('formas_pagamento')->restrictOnDelete();
            $table->unique(['banca_id', 'forma_pagamento_id']);

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
        Schema::dropIfExists('banca_forma_pagamento');
    }
};
