<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Tambahkan fillable agar datanya bisa disimpan dari form Add Task
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'platform_distribution',
        'deadline',
        'priority',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    // Relasi ke tabel Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Anggota yang ditugaskan pada task ini
    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id')->withTimestamps();
    }
}