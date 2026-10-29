<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'platform' => 'nullable|string|max:100',
        ]);

        $project = Project::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'platform' => $request->platform ?? 'Multi-Platform',
        ]);

        return redirect()->route('dashboard', ['project_id' => $project->id])
            ->with('success', 'Project baru berhasil dibuat!');
    }

    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'platform' => 'nullable|string|max:100',
        ]);

        $project->update($request->only(['name', 'description', 'platform']));

        return back()->with('success', 'Project berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }

        $project->delete();

        return redirect()->route('dashboard')->with('success', 'Project berhasil dihapus!');
    }

    public function exportCsv(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }

        $tasks = $project->tasks()->orderBy('status')->orderBy('created_at', 'desc')->get();

        $filename = 'OurPlan_' . \Illuminate\Support\Str::slug($project->name) . '_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($tasks, $project) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM untuk kompatibilitas Microsoft Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom CSV
            fputcsv($file, ['ID', 'Project', 'Judul Konten', 'Platform', 'Status Workflow', 'Prioritas', 'Deadline', 'Deskripsi', 'Dibuat Pada']);

            foreach ($tasks as $task) {
                fputcsv($file, [
                    $task->id,
                    $project->name,
                    $task->title,
                    $task->platform_distribution,
                    $task->status,
                    $task->priority,
                    $task->deadline ? $task->deadline->format('Y-m-d') : '-',
                    $task->description ?? '-',
                    $task->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
