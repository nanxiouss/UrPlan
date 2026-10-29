<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Habit::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'streak_count' => 0,
            'last_completed_at' => null,
        ]);

        return back()->with('success', 'Habit baru berhasil ditambahkan! Ayo mulai bangun kebiasaanmu! 🌱');
    }

    public function checkIn(Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        if ($habit->isCompletedToday()) {
            return back()->with('success', 'Habit ini sudah kamu check-in hari ini! Pertahankan konsistensinya! ✨');
        }

        // Jika terakhir dikerjakan kemarin, tambah streak. Jika terputus / baru pertama, mulai dari 1.
        if ($habit->last_completed_at && $habit->last_completed_at->isYesterday()) {
            $habit->streak_count += 1;
        } else {
            $habit->streak_count = 1;
        }

        $habit->last_completed_at = now()->toDateString();
        $habit->save();

        return back()->with('success', "🔥 Mantap! Check-in berhasil, streak kamu sekarang {$habit->streak_count} hari berturut-turut!");
    }

    public function update(Request $request, Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $habit->update([
            'name' => $request->name,
        ]);

        if ($request->boolean('reset_streak')) {
            $habit->update([
                'streak_count' => 0,
                'last_completed_at' => null,
            ]);
        }

        return back()->with('success', 'Habit berhasil diperbarui!');
    }

    public function destroy(Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        $habit->delete();

        return back()->with('success', 'Habit berhasil dihapus!');
    }
}
