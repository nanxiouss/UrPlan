<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
    /**
     * Menambahkan akun anggota tim baru di bawah user / tim leader saat ini
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $ownerId = $currentUser->workspace_owner_id;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'position' => ['nullable', 'string', 'max:100'],
        ]);

        $member = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'owner_id' => $ownerId,
            'role' => 'team_member',
            'position' => $request->position ?? 'Anggota Tim',
        ]);

        return back()->with('success', "Anggota tim '{$member->name}' (" . ($member->position ?: 'Anggota') . ") berhasil ditambahkan! Mereka dapat langsung login menggunakan email & password yang didaftarkan.");
    }

    /**
     * Menghapus akun anggota tim
     */
    public function destroy(User $member)
    {
        $currentUser = Auth::user();
        $ownerId = $currentUser->workspace_owner_id;

        // Pastikan anggota tersebut memang milik tim ini dan bukan diri sendiri
        if ($member->owner_id !== $ownerId || $member->id === $currentUser->id) {
            abort(403, 'Anda tidak memiliki hak untuk menghapus pengguna ini.');
        }

        $memberName = $member->name;
        $member->delete();

        return back()->with('success', "Anggota tim '{$memberName}' berhasil dihapus dari tim.");
    }
}
