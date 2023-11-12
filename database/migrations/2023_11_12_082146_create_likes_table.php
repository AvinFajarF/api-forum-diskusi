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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            // relasi ke table users
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users');

            // relasi ke table threds
            $table->unsignedBigInteger('threads_id');

            $table->foreign('threads_id')->references('id')->on('threads');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
