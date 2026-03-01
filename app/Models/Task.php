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
        'priority',
        'status',
    ];

    // Relasi ke tabel Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}