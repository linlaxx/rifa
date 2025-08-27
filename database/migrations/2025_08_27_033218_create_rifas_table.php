<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('rifas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre');
        $table->text('descripcion')->nullable();
        $table->string('fotos')->nullable(); // luego lo ajustamos si quieres múltiples fotos
        $table->decimal('precio_boleto', 8, 2);
        $table->integer('total_boletos');
        $table->enum('estado', ['activa', 'finalizada'])->default('activa');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rifas');
    }
};
