<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Notification;
use App\Models\User;
use App\Mail\TaskAssignedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'platform_distribution' => 'required|string',
            'status' => 'required|in:Idea,Draft,In Progress,Review,Scheduled,Published',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'nullable|date',
            'description' => 'nullable|string',
            'assignee_ids' => 'nullable|array',
            'assignee_ids.*' => 'exists:users,id',
        ]);

        $project = Project::findOrFail($request->project_id);

        // Pastikan project dapat diakses oleh user atau timnya
        if (!$project->isAccessibleBy($user)) {
            abort(403, 'Anda tidak memiliki akses ke project ini.');
        }

        $task = Task::create([
            'project_id' => $project->id,
            'title' => $request->title,
            'platform_distribution' => $request->platform_distribution,
            'status' => $request->status,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'description' => $request->description,
        ]);

        // Hubungkan anggota yang ditugaskan (Multi-Assignees)
        $assigneeIds = $request->input('assignee_ids', []);
        if (!empty($assigneeIds)) {
            $task->assignees()->sync($assigneeIds);

            // Kirim notifikasi sistem & Email ke masing-masing anggota yang ditugaskan
            $deadlineStr = $task->deadline ? $task->deadline->translatedFormat('d M Y') : 'Tanpa Deadline';
            $assigneeUsers = User::whereIn('id', $assigneeIds)->get();

            foreach ($assigneeUsers as $assigneeUser) {
                // 1. Notifikasi database
                Notification::create([
                    'user_id' => $assigneeUser->id,
                    'title' => '📌 Tugas Baru Ditugaskan: ' . $task->title,
                    'message' => "Anda ditugaskan oleh {$user->name} pada tugas '{$task->title}' [{$task->priority} - {$task->platform_distribution}] di proyek '{$project->name}' (Deadline: {$deadlineStr}).",
                    'is_read' => false,
                ]);

                // 2. Notifikasi Email (Tetap terkirim meskipun web ditutup)
                try {
                    Mail::to($assigneeUser->email)->send(new TaskAssignedMail($task, $assigneeUser, $user));
                } catch (\Exception $e) {
                    Log::warning("Gagal mengirim email penugasan ke {$assigneeUser->email}: " . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'Task baru berhasil ditambahkan dan notifikasi penugasan telah dikirimkan!');
    }

    public function update(Request $request, Task $task)
    {
        $user = Auth::user();

        if (!$task->project->isAccessibleBy($user)) {
            abort(403, 'Anda tidak memiliki akses ke task ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'platform_distribution' => 'required|string',
            'status' => 'required|in:Idea,Draft,In Progress,Review,Scheduled,Published',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'nullable|date',
            'description' => 'nullable|string',
            'assignee_ids' => 'nullable|array',
            'assignee_ids.*' => 'exists:users,id',
        ]);

        $task->update([
            'title' => $request->title,
            'platform_distribution' => $request->platform_distribution,
            'status' => $request->status,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'description' => $request->description,
        ]);

        // Update Multi-Assignees & Kirim notifikasi untuk anggota yang baru ditambahkan
        $oldAssignees = $task->assignees->pluck('id')->toArray();
        $newAssignees = $request->input('assignee_ids', []);
        $task->assignees()->sync($newAssignees);

        $addedAssigneeIds = array_diff($newAssignees, $oldAssignees);
        if (!empty($addedAssigneeIds)) {
            $deadlineStr = $task->deadline ? $task->deadline->translatedFormat('d M Y') : 'Tanpa Deadline';
            $addedUsers = User::whereIn('id', $addedAssigneeIds)->get();

            foreach ($addedUsers as $addedUser) {
                Notification::create([
                    'user_id' => $addedUser->id,
                    'title' => '📌 Penugasan Tugas: ' . $task->title,
                    'message' => "Anda ditugaskan oleh {$user->name} pada tugas '{$task->title}' di proyek '{$task->project->name}' (Deadline: {$deadlineStr}).",
                    'is_read' => false,
                ]);

                try {
                    Mail::to($addedUser->email)->send(new TaskAssignedMail($task, $addedUser, $user));
                } catch (\Exception $e) {
                    Log::warning("Gagal mengirim email penugasan ke {$addedUser->email}: " . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'Task berhasil diperbarui!');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $user = Auth::user();

        if (!$task->project->isAccessibleBy($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:Idea,Draft,In Progress,Review,Scheduled,Published',
        ]);

        $task->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status task berhasil diperbarui!',
            'task' => $task
        ]);
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();

        if (!$task->project->isAccessibleBy($user)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus task ini.');
        }

        $task->delete();

        return back()->with('success', 'Task berhasil dihapus!');
    }
}