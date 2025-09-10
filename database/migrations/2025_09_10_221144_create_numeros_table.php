<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('numeros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable(); // 👈 Nombre opcional que ponga el admin
            $table->string('numero')->unique();   // 👈 Número único
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('numeros');
    }
};
