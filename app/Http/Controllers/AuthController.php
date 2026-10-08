<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Exception;

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

        try {
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
                    'user' => $user->nama ?? $user->username,
                    'aksi' => 'Login',
                    'deskripsi' => ($user->nama ?? $user->username) . " ({$user->username}) berhasil masuk ke sistem.",
                ]);

                $greeting = $user->nama ?? $user->username;
                return redirect()->intended(route('admin.dashboard'))
                    ->with('succes', 'Selamat datang kembali, ' . $greeting);
            }

            return back()->withErrors([
                'username' => 'Username atau password yang Anda masukkan salah.',
            ])->onlyInput('username');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            return back()->withInput($request->only('username'))
                ->with('error', 'Terjadi kesalahan saat memproses login: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman formulir registrasi admin/petugas baru.
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
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        DB::beginTransaction();
        try {
            // Generate default email jika kosong
            $email = $validated['email'] ?? ($validated['username'] . '@siperalat.local');

            $user = User::create([
                'nama' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $email,
                'password' => Hash::make($validated['password']),
                'role' => 'petugas',
                'status' => 'aktif',
            ]);

            // Catat log aktivitas registrasi
            Log::create([
                'user' => $user->nama,
                'aksi' => 'Register',
                'deskripsi' => "Akun pengguna baru {$user->nama} ({$user->username}) berhasil didaftarkan.",
            ]);

            DB::commit();

            return redirect()->route('login')->with('succes', 'Registrasi berhasil! Silakan masuk menggunakan akun Anda.');
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage());
        }
    }

    /**
     * Memproses logout pengguna dan mencatat log aktivitas.
     */
    public function logout_proses(Request $request)
    {
        try {
            $user = Auth::user();
            $userName = $user ? ($user->nama ?? $user->username) : 'Pengguna';

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::create([
                'user' => $userName,
                'aksi' => 'Logout',
                'deskripsi' => "{$userName} Logouut",
            ]);

            return redirect()->route('login')->with('succes', 'Selamat Tinggal');
        } catch (Exception $e) {
            return redirect()->route('login')->with('succes', 'Selamat Tinggal');
        }
    }

    // ==========================================
    // CRUD USER MANAGEMENT (Pengelolaan Pengguna)
    // ==========================================

    /**
     * Menampilkan daftar semua pengguna admin / petugas.
     */
    public function user_index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('Admin.User.index', compact('users'));
    }

    /**
     * Menampilkan formulir tambah pengguna baru.
     */
    public function user_create()
    {
        return view('Admin.User.create');
    }

    /**
     * Menyimpan data pengguna baru ke database.
     */
    public function user_store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['nullable', 'in:admin,petugas,peminjam'],
            'status' => ['nullable', 'in:aktif,nonaktif'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        DB::beginTransaction();
        try {
            $email = $validated['email'] ?? ($validated['username'] . '@siperalat.local');

            $user = User::create([
                'nama' => $validated['nama'],
                'username' => $validated['username'],
                'email' => $email,
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'] ?? 'petugas',
                'status' => $validated['status'] ?? 'aktif',
            ]);

            $currentUser = Auth::user();
            Log::create([
                'user' => $currentUser ? ($currentUser->nama ?? $currentUser->username) : 'Sistem',
                'aksi' => 'Tambah User',
                'deskripsi' => "Menambahkan user baru: {$user->nama} ({$user->username})",
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('succes', 'Pengguna baru berhasil ditambahkan.');
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->except('password'))
                ->with('error', 'Terjadi kesalahan saat menambahkan pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan formulir edit data pengguna.
     */
    public function user_edit($id)
    {
        $user = User::findOrFail($id);
        return view('Admin.User.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna di database.
     */
    public function user_update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Data pengguna tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['nullable', 'in:admin,petugas,peminjam'],
            'status' => ['nullable', 'in:aktif,nonaktif'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah terdaftar untuk pengguna lain.',
            'password.min' => 'Password baru minimal 6 karakter.',
        ]);

        DB::beginTransaction();
        try {
            $user->nama = $validated['nama'];
            $user->username = $validated['username'];

            if (!empty($validated['email'])) {
                $user->email = $validated['email'];
            }

            if (!empty($validated['role'])) {
                $user->role = $validated['role'];
            }

            if (!empty($validated['status'])) {
                $user->status = $validated['status'];
            }

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            $currentUser = Auth::user();
            Log::create([
                'user' => $currentUser ? ($currentUser->nama ?? $currentUser->username) : 'Sistem',
                'aksi' => 'Update User',
                'deskripsi' => "Memperbarui data user ID {$user->id}: {$user->nama} ({$user->username})",
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('succes', 'Data pengguna berhasil diperbarui.');
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput($request->except('password'))
                ->with('error', 'Terjadi kesalahan saat memperbarui pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data pengguna dari database.
     */
    public function user_destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            // Mencegah admin menghapus akunnya sendiri yang sedang aktif
            if (Auth::id() == $user->id) {
                return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
            }

            $deletedName = $user->nama ?? $user->username;
            $deletedUsername = $user->username;
            $user->delete();

            $currentUser = Auth::user();
            Log::create([
                'user' => $currentUser ? ($currentUser->nama ?? $currentUser->username) : 'Sistem',
                'aksi' => 'Hapus User',
                'deskripsi' => "Menghapus akun user: {$deletedName} ({$deletedUsername})",
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('succes', 'Pengguna berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
