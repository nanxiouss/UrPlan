<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description', 'platform'];

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Tasks
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Cek apakah user berhak mengakses project (baik sebagai owner maupun sebagai team member)
    public function isAccessibleBy(User $user): bool
    {
        $ownerId = $user->workspace_owner_id;
        return $this->user_id === $ownerId;
    }
}