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
        Schema::create('TPresenceHistories', function (Blueprint $table) {
            $table->bigIncrements("PresenceId");
            $table->unsignedBigInteger('TicketId');
            $table->date('scan_date');
            $table->boolean('status')->default(0);
            $table->rememberToken();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TPresenceHistories');
    }
};
