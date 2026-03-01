<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('name'); // Contoh: "Riset Ide Konten", "Posting Instagram"
            $table->integer('streak_count')->default(0); // Menghitung streak berturut-turut
            $table->date('last_completed_at')->nullable(); // Mencatat kapan terakhir dilakukan
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};