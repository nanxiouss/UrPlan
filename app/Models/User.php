<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Project;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'owner_id',
        'role',
        'position',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function habits()
    {
        return $this->hasMany(Habit::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Anggota tim yang berada di bawah user ini (jika user adalah leader/owner)
    public function teamMembers()
    {
        return $this->hasMany(User::class, 'owner_id');
    }

    // Pemilik / Leader dari user ini (jika user adalah team_member)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Tugas yang ditugaskan ke user ini
    public function assignedTasks()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id')->withTimestamps();
    }

    // ID pemilik workspace
    public function getWorkspaceOwnerIdAttribute()
    {
        return $this->owner_id ?: $this->id;
    }

    // Mengambil seluruh user dalam tim (Leader + semua anggota)
    public function getTeamWithLeaderAttribute()
    {
        $ownerId = $this->workspace_owner_id;
        return User::where('id', $ownerId)
            ->orWhere('owner_id', $ownerId)
            ->orderBy('id', 'asc')
            ->get();
    }

    // Helper untuk inisial nama
    public function getInitialsAttribute()
    {
        $words = explode(' ', trim($this->name));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= strtoupper(mb_substr($w, 0, 1));
        }
        return $initials ?: 'U';
    }
}