<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChessTable extends Migration
{
    public function up()
    {
        Schema::create('chess', function (Blueprint $table) {
            $table->id();
            $table->string('player1_id', 8);
            $table->string('player2_id', 8);
            $table->string('player1_color');
            $table->string('player2_color');
            $table->string('game_url')->nullable();
            $table->enum('status', ['active', 'finished']);
            $table->text('history')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('player1_id')->references('user_id')->on('users');
            $table->foreign('player2_id')->references('user_id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chess');
    }
}
