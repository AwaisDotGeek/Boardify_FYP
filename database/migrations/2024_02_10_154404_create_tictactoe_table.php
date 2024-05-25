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
        Schema::create('tictactoe', function (Blueprint $table) {
            $table->id();
            $table->string('player1_id');
            $table->string('player2_id');
            $table->string('game_url');
            $table->enum('status', ['active', 'finished']);
            $table->text('history')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tictactoe');
    }
};
