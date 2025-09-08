<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('boleto_id')->unique(); // cada boleto solo una vez
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono');
            $table->string('estado');
            $table->timestamp('expira_en');
            $table->timestamps();

            $table->foreign('boleto_id')
                  ->references('id')
                  ->on('boletos')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservas');
    }
};
