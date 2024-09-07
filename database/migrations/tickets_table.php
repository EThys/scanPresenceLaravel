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
        Schema::create('TTickets', function (Blueprint $table) {
            $table->bigIncrements('TicketId');
            $table->string('serie')->nullable();
            $table->string('name')->nullable();
            $table->decimal('price', 10, 2)->nullable(); 
            $table->string('barcode')->nullable(); 
            $table->string('qrcode'); 
            $table->string('eventName')->nullable(); 
            $table->string('eventAddress')->nullable();
            $table->dateTime('eventDate')->nullable(); 
            $table->dateTime('eventEndDate')->nullable(); 
            $table->string('eventCurrency', 3)->nullable(); 
            $table->string('eventId')->nullable(); 
            $table->enum('status', [0, 1])->default(0);
            $table->date('last_reset_date')->nullable();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TTickets');
    }
};
