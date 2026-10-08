<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman formulir login.
     */
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('Auth.Login.index');
    }

    /**
     * Memproses autentikasi login pengguna.
     */
    public function login_proses(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Cek status keaktifan akun
            if ($user->status === 'nonaktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'username' => 'Akun Anda sedang nonaktif. Silakan hubungi administrator.',
                ])->onlyInput('username');
            }

            $request->session()->regenerate();

            // Perbarui waktu login terakhir
            $user->last_login_at = now();
            $user->save();

            // Catat log aktivitas login
            Log::create([
                'user' => $user->username,
                'aksi' => 'Login',
                'deskripsi' => "{$user->username} berhasil masuk ke sistem.",
            ]);

            return redirect()->intended(route('admin.dashboard'))
                ->with('succes', 'Selamat datang kembali, ' . $user->username);
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Menampilkan halaman formulir registrasi.
     */
    public function register()
    {
        return view('Auth.Register.index');
    }

    /**
     * Memproses pendaftaran user baru.
     */
    public function register_proses(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        // Catat log aktivitas registrasi
        Log::create([
            'user' => $user->username,
            'aksi' => 'Register',
            'deskripsi' => "Akun pengguna baru {$user->username} berhasil didaftarkan.",
        ]);

        return redirect()->route('login')->with('succes', 'Registrasi berhasil! Silakan masuk menggunakan akun Anda.');
    }

    /**
     * Memproses logout pengguna dan mencatat log aktivitas.
     */
    public function logout_proses(Request $request)
    {
        $user = Auth::user();
        $userName = $user ? $user->username : 'Pengguna';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::create([
            'user' => $userName,
            'aksi' => 'Logout',
            'deskripsi' => "{$userName} Logout",
        ]);

        return redirect()->route('login')->with('succes', 'Selamat Tinggal');
    }


    /**
     * Menampilkan daftar semua pengguna.
     */
    public function user_index(Request $request)
    {
        $users = User::latest()->paginate(10)->withQueryString();

        return view('Admin.User.index', compact('users'));
    }

    /**
     * Menyimpan data pengguna baru ke database.
     */
    public function user_store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,petugas,peminjam'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        $currentUser = Auth::user();
        Log::create([
            'user' => $currentUser ? $currentUser->username : 'Sistem',
            'aksi' => 'Tambah User',
            'deskripsi' => "Menambahkan user baru: {$user->username} ({$user->role})",
        ]);

        return redirect()->route('admin.user.index')
            ->with('succes', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Memperbarui data pengguna di database.
     */
    public function user_update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,petugas,peminjam'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah terdaftar untuk pengguna lain.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar untuk pengguna lain.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $currentUser = Auth::user();
        Log::create([
            'user' => $currentUser ? $currentUser->username : 'Sistem',
            'aksi' => 'Update User',
            'deskripsi' => "Memperbarui data user: {$user->username}",
        ]);

        return redirect()->route('admin.user.index')
            ->with('succes', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus data pengguna dari database.
     */
    public function user_destroy($id)
    {
        $user = User::findOrFail($id);

        // Mencegah admin menghapus akunnya sendiri yang sedang login
        if (Auth::id() == $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        $deletedUsername = $user->username;
        $user->delete();

        $currentUser = Auth::user();
        Log::create([
            'user' => $currentUser ? $currentUser->username : 'Sistem',
            'aksi' => 'Hapus User',
            'deskripsi' => "Menghapus akun user: {$deletedUsername}",
        ]);

        return redirect()->route('admin.user.index')
            ->with('succes', 'Pengguna berhasil dihapus.');
    }
}
