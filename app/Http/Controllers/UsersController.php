<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index() {
        $authUser = Auth::user();
        $users = User::all();
        
        if ($authUser && $authUser->level == 1) {
            return view('admin.users.index', compact('authUser', 'users'));
        } else if ($authUser && $authUser->level == 2) {
            return view('petugas.index', compact('authUser'));
        }
        
        return redirect('/');
    }

    public function create() {
        $authUser = Auth::user();
        return view('admin.users.create', compact('authUser'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'required|string|max:16',
            'level' => 'required|integer',
            'no_telepon' => 'required|string|max:15',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
            'nik.required' => 'NIK harus diisi',
            'nik.max' => 'NIK maksimal 16 karakter',
            'level.required' => 'Level harus diisi',
            'no_telepon.required' => 'No Telepon harus diisi',
            'no_telepon.max' => 'No Telepon maksimal 15 karakter',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nik' => $request->nik,
            'level' => $request->level,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('dashboard.users')->with('success', 'Sukses menambah User');
    }

    public function edit(User $user) {
        $authUser = Auth::user();
        return view('admin.users.edit', compact('user', 'authUser'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nik' => 'required|string|max:16',
            'level' => 'required|integer',
            'no_telepon' => 'required|string|max:15',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'nik.required' => 'NIK harus diisi',
            'nik.max' => 'NIK maksimal 16 karakter',
            'level.required' => 'Level harus diisi',
            'no_telepon.required' => 'No Telepon harus diisi',
            'no_telepon.max' => 'No Telepon maksimal 15 karakter',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ], [
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak sesuai',
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'level' => $request->level,
            'no_telepon' => $request->no_telepon,
        ]);

        return redirect()->route('dashboard.users')->with('success', 'Sukses mengupdate User');
    }

    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('dashboard.users')->with('success', 'Sukses menghapus User');
    }
}
