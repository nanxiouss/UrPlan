<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Habit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Admin UrPlan',
            'email' => 'admin@urplan.test',
            'password' => Hash::make('password123'), // Password default
            'role' => 'admin',
        ]);

        // 2. Buat Akun User (Content Creator)
        $user = User::create([
            'name' => 'Budi Creator',
            'email' => 'budi@urplan.test',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 3. Buat 1 Project untuk Budi
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Tech YouTube Channel',
            'description' => 'Fokus konten review gadget dan tutorial coding',
            'platform' => 'YouTube',
        ]);

        // 4. Buat Task untuk Kanban Board (Status berbeda-beda)
        Task::create([
            'project_id' => $project->id,
            'title' => 'Review Laptop Gaming Terbaru',
            'description' => 'Bahas spesifikasi, harga, dan test main game AAA.',
            'platform_distribution' => 'YouTube',
            'priority' => 'High',
            'status' => 'Idea', // Masih ide
        ]);

        Task::create([
            'project_id' => $project->id,
            'title' => 'Tutorial Laravel MVC untuk Pemula',
            'description' => 'Langkah-langkah setup database dan migration.',
            'platform_distribution' => 'YouTube',
            'priority' => 'Medium',
            'status' => 'In Progress', // Sedang dikerjakan
        ]);

        // 5. Buat Data Habit Tracker
        Habit::create([
            'user_id' => $user->id,
            'name' => 'Riset Ide Konten Harian',
            'streak_count' => 5, // Sudah konsisten 5 hari beruntun!
            'last_completed_at' => now(),
        ]);
    }
}