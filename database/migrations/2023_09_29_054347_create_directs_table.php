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
        Schema::create('directs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('dm');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recieving_id')->constrained('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directs');
    }
};
