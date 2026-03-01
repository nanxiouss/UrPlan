<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // ... fungsi index / yang lainnya ...

    public function store(Request $request)
    {
        // Validasi data yang masuk dari form modal
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'platform_distribution' => 'required|string',
            'status' => 'required|string',
            'priority' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Simpan ke database
        Task::create([
            'project_id' => $request->project_id,
            'title' => $request->title,
            'platform_distribution' => $request->platform_distribution,
            'status' => $request->status,
            'priority' => $request->priority,
            'description' => $request->description,
        ]);

        // Kembalikan pengguna ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Task baru berhasil ditambahkan!');
    }
}