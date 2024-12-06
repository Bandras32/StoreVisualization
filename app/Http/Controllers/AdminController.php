<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function showUsers()
    {
        $users = User::paginate(5);
        return view('admin.users.index', ['users' => $users]);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user', // Validates role input
        ]);

        $user->role = $request->input('role');
        $user->save();

        return redirect()->route('admin.users')->with('status', 'Role updated successfully!');
    }

    public function deleteUser(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }
}
