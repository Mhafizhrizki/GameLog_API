<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('game_logs', function (Blueprint $table) {
            $table->id();
            // Menghubungkan log dengan user yang login
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 

            // Kolom sesuai instruksi Github
            $table->unsignedBigInteger('rawg_id');
            $table->string('title');
            $table->string('image')->nullable();
            $table->string('status')->default('playing'); // wishlist, playing, completed
            $table->integer('personal_rating')->default(0); // 0 sampai 5

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('game_logs');
    }
};