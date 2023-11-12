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
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            // relasi ke table users
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users');

            // relasi ke table tags
            $table->unsignedBigInteger('tags_id');

            $table->foreign('tags_id')->references('id')->on('tags');

            $table->string("title");
            $table->string("description");
            $table->integer("like");
            $table->integer("visitor");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('threads');
    }
};
