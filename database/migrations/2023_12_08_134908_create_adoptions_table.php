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
        Schema::create('adoptions', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('email', 255);
            $table->string('cpf', 14);
            $table->string('contact', 20);
            $table->text('observations');
            $table->enum('status', ['PENDENTE', 'NEGADO', 'APROVADO']);
            $table->unsignedBigInteger('pet_id');
            $table->timestamps();

            $table->foreign('pet_id')->references('id')->on('pets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adoptions');
    }
};
