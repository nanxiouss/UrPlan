<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;
use App\Models\Habit;
use App\Models\Project;

use App\Models\Notification;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // --- 1. LOGIKA UNTUK ADMIN ---
        if ($user->role === 'admin' || $user->name === 'Admin UrPlan' || $user->email === 'admin@urplan.com' || $user->email === 'admin@urplan.test') {

            // Hitung total statistik global
            $totalUsers = User::count();
            $totalCreators = User::where('role', 'user')->whereNull('owner_id')->count();
            $totalTeamMembers = User::where('role', 'team_member')->orWhereNotNull('owner_id')->count();
            $totalTasks = Task::count();
            $totalProjects = Project::count();
            $publishedTasks = Task::where('status', 'Published')->count();

            // Distribusi platform konten
            $tasksByPlatform = Task::selectRaw('platform_distribution, count(*) as total')
                ->whereNotNull('platform_distribution')
                ->groupBy('platform_distribution')
                ->pluck('total', 'platform_distribution')
                ->toArray();

            // Distribusi status workflow
            $tasksByStatus = Task::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            // Ambil seluruh user non-admin beserta relasi untuk modal 'Lihat Semua' & 'Detail'
            $allUsers = User::where('id', '!=', $user->id)
                ->withCount(['projects', 'habits'])
                ->with(['projects' => function($q) {
                    $q->withCount('tasks')->orderBy('created_at', 'desc');
                }])
                ->orderBy('created_at', 'desc')
                ->get();

            $recentUsers = $allUsers->take(6);
            $isMaintenance = cache()->get('system_maintenance_mode', false);

            // Riwayat broadcast pengumuman terkini
            $recentBroadcasts = Notification::select('title', 'message', 'created_at', \Illuminate\Support\Facades\DB::raw('count(*) as recipients_count'))
                ->groupBy('title', 'message', 'created_at')
                ->latest('created_at')
                ->take(5)
                ->get();

            return view('admin.dashboard', compact(
                'totalUsers',
                'totalCreators',
                'totalTeamMembers',
                'totalTasks',
                'totalProjects',
                'publishedTasks',
                'tasksByPlatform',
                'tasksByStatus',
                'recentUsers',
                'allUsers',
                'recentBroadcasts',
                'isMaintenance'
            ));
        }

        // --- 2. LOGIKA UNTUK USER BIASA & ANGGOTA TIM ---
        $ownerId = $user->workspace_owner_id;
        $projects = Project::where('user_id', $ownerId)->latest()->get();

        // Buat default project jika belum ada sama sekali
        if ($projects->isEmpty()) {
            $defaultProject = Project::create([
                'user_id' => $ownerId,
                'name' => 'Konten Utama',
                'description' => 'Workspace perencanaan konten utama',
                'platform' => 'Multi-Platform',
            ]);
            $projects = collect([$defaultProject]);
            $project = $defaultProject;
        } else {
            if ($request->filled('project_id')) {
                $project = $projects->firstWhere('id', $request->project_id) ?? $projects->first();
            } else {
                $project = $projects->first();
            }
        }

        $habits = Habit::where('user_id', $user->id)->get();
        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->get();

        // Ambil data seluruh anggota tim dalam workspace ini (Leader + Members)
        $teamMembers = User::where('id', $ownerId)
            ->orWhere('owner_id', $ownerId)
            ->orderBy('id', 'asc')
            ->get();

        // Ambil khusus anggota tim bawahan untuk modal manajemen tim
        $managedMembers = User::where('owner_id', $ownerId)
            ->orderBy('created_at', 'desc')
            ->get();

        $tasksByStatus = [];
        $platformStats = [
            'YouTube' => 0,
            'TikTok' => 0,
            'Instagram' => 0,
            'Blog' => 0,
        ];

        if ($project) {
            $tasks = Task::with('assignees')
                ->where('project_id', $project->id)
                ->orderBy('created_at', 'desc')
                ->get();
            $tasksByStatus = $tasks->groupBy('status');

            $dbPlatformStats = Task::where('project_id', $project->id)
                ->selectRaw('platform_distribution, count(*) as total')
                ->groupBy('platform_distribution')
                ->pluck('total', 'platform_distribution')
                ->toArray();

            $platformStats = array_merge($platformStats, $dbPlatformStats);
        }

        return view('user.dashboard', [
            'habits' => $habits,
            'projects' => $projects,
            'project' => $project,
            'platformStats' => $platformStats,
            'tasksByStatus' => $tasksByStatus,
            'unreadNotifications' => $unreadNotifications,
            'teamMembers' => $teamMembers,
            'managedMembers' => $managedMembers,
            'isTeamLeader' => ($user->owner_id === null),
            'isMaintenance' => cache()->get('system_maintenance_mode', false),
        ]);
    }

    public function broadcast(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->name !== 'Admin UrPlan' && $user->email !== 'admin@urplan.com' && $user->email !== 'admin@urplan.test') {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target' => 'required',
        ]);

        if ($request->target === 'all') {
            $targetUsers = User::where('id', '!=', $user->id)->get();
        } else {
            $targetUsers = User::where('id', $request->target)->get();
        }

        foreach ($targetUsers as $targetUser) {
            Notification::create([
                'user_id' => $targetUser->id,
                'title' => $request->title,
                'message' => $request->message,
                'is_read' => false,
            ]);
        }

        return back()->with('success', 'Broadcast pengumuman berhasil dikirimkan ke ' . $targetUsers->count() . ' pengguna!');
    }

    public function deleteBroadcast(Request $request)
    {
        $admin = Auth::user();
        if ($admin->role !== 'admin' && $admin->name !== 'Admin UrPlan' && $admin->email !== 'admin@urplan.com' && $admin->email !== 'admin@urplan.test') {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string',
            'created_at' => 'required',
        ]);

        $deletedCount = Notification::where('title', $request->title)
            ->where('created_at', $request->created_at)
            ->delete();

        return back()->with('success', "Pengumuman siaran berhasil ditarik/dihapus dari {$deletedCount} pengguna.");
    }

    public function deleteUser(User $user)
    {
        $admin = Auth::user();
        if ($admin->role !== 'admin' && $admin->name !== 'Admin UrPlan' && $admin->email !== 'admin@urplan.com' && $admin->email !== 'admin@urplan.test') {
            abort(403);
        }

        if ($user->id === $admin->id) {
            return back()->with('error', 'Tidak dapat menghapus akun admin sendiri!');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "Pengguna {$userName} berhasil dihapus dari sistem.");
    }

    public function toggleMaintenance(Request $request)
    {
        $admin = Auth::user();
        if ($admin->role !== 'admin' && $admin->name !== 'Admin UrPlan' && $admin->email !== 'admin@urplan.com' && $admin->email !== 'admin@urplan.test') {
            abort(403);
        }

        $isMaintenance = cache()->get('system_maintenance_mode', false);
        cache()->forever('system_maintenance_mode', !$isMaintenance);

        $status = !$isMaintenance ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Maintenance mode berhasil {$status}!");
    }
}
