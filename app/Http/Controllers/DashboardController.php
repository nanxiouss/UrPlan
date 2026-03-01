<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;
use App\Models\Habit;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // --- 1. LOGIKA UNTUK ADMIN ---
        if ($user->name === 'Admin UrPlan' || $user->email === 'admin@urplan.com') {

            // Hitung total statistik global
            $totalUsers = User::count();
            $totalTasks = Task::count();

            // Ambil 5 user terbaru yang mendaftar (untuk tabel Manajemen Pengguna)
            // Kode baru (mengecualikan admin berdasarkan nama):
            $recentUsers = User::where('id', '!=', $user->id)
                   ->orderBy('created_at', 'desc')
                   ->take(5)
                   ->get();

            // Kirim semua data ke view admin
            return view('admin.dashboard', compact('totalUsers', 'totalTasks', 'recentUsers'));
        }

        // --- 2. LOGIKA UNTUK USER BIASA ---
        $project = Project::where('user_id', $user->id)->latest()->first();
        $habits = Habit::where('user_id', $user->id)->get();

        $tasksByStatus = [];
        $platformStats = [];

        if ($project) {
            $tasks = Task::where('project_id', $project->id)->get();
            $tasksByStatus = $tasks->groupBy('status');

            $platformStats = Task::where('project_id', $project->id)
                ->selectRaw('platform_distribution, count(*) as total')
                ->groupBy('platform_distribution')
                ->pluck('total', 'platform_distribution')
                ->toArray();
        }

        return view('user.dashboard', [
            'habits' => $habits,
            'project' => $project,
            'platformStats' => $platformStats,
            'tasksByStatus' => $tasksByStatus
        ]);
    }
}
