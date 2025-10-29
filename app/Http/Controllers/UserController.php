<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $users = User::paginate(10);
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        return view('users.index', compact('users'));
    }

    /**
     * create
     *
     * @return void
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        return view('users.create');
    }

    /**
     * store
     *
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,kasir'
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        return redirect()->route('users.index')->with('success', 'Berhasil Tambah Akun Baru');
    }

    /**
     * edit
     *
     * @return void
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * update
     *
     * @return void
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role'  => 'required|string'
        ];

        // Jika user ingin ganti password
        if ($request->filled('password_lama') || $request->filled('password_baru')) {
            $rules['password_lama'] = 'required';
            $rules['password_baru'] = 'required|min:6';
        }

        $validated = $request->validate($rules);

        // Update data umum
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        // Proses password jika diisi
        if ($request->filled('password_lama') && $request->filled('password_baru')) {

            // ✅ Periksa password lama
            if (!Hash::check($request->password_lama, $user->password)) {
                return back()->with('error', 'Password Lama Tidak Sesuai!')->withInput();
            }

            // ✅ Periksa apakah password baru sama dengan yang lama
            if ($request->password_lama === $request->password_baru) {
                return back()->with('error', 'Password Baru Tidak Boleh Sama Dengan Password Lama!')->withInput();
            }

            // ✅ Jika semua valid, simpan password baru
            $user->password = Hash::make($request->password_baru);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Data User Berhasil Diperbarui.');
    }

    /**
     * destroy
     *
     * @return void
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Tidak Bisa Hapus Akun Sendiri');
        }

        $user->delete();
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }
        return redirect()->route('users.index')->with('success', 'Akun Berhasil Dihapus');
    }

    /**
     * profile
     *
     * @return void
     */
    public function profile()
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    /**
     * updateProfile
     *
     * @return void
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        //jika user isi password
        if ($request->filled('password_lama') || $request->filled('password_baru')) {
            $rules['password_lama'] = 'required';
            $rules['password_baru'] = 'required|min:8';
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password_lama') && $request->filled('password_baru')) {
            if (!Hash::check($request->password_lama, $user->password)) {
                return back()->with('error', 'Password Lama Tidak Sesuai !')->withInput();
            }
            if ($request->password_lama === $request->password_baru) {
                return back()->with('error', 'Password Baru Tidak Boleh Sama Dengan Password Lama !')->withInput();
            }
            $user->password = Hash::make($request->password_baru);
        }

        $user->save();

        return back()->with('success', 'Profil Berhasil Diperbaharui');
    }
}
