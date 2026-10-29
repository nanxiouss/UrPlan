<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'streak_count',
        'last_completed_at',
    ];

    protected $casts = [
        'last_completed_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isCompletedToday(): bool
    {
        return $this->last_completed_at && $this->last_completed_at->isToday();
    }
}
