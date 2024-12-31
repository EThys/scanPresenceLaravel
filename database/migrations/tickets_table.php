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
        Schema::create('ttickets', function (Blueprint $table) {
            $table->bigIncrements('TicketId');
            $table->string('nom');
            $table->string('postnom')->nullable();
            $table->string('prenom');
            $table->integer('nombre_des_personnes');
            $table->string('civilite');
            $table->boolean('presence')->default(false);
            $table->timestamps();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ttickets');
    }
};
