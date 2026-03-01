<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel projects
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('platform_distribution')->nullable();
            $table->date('deadline')->nullable();

            // Prioritas Task
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');

            // Status Workflow (Untuk Kanban Board)
            $table->enum('status', ['Idea', 'Draft', 'In Progress', 'Review', 'Scheduled', 'Published'])->default('Idea');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
