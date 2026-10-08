<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PeminjamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggans = User::where('role', 'peminjam')->latest()->paginate(10);

        return view('Admin.Pelanggan.index', compact('pelanggans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'status' => 'required',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'peminjam';

        $user = User::create($data);

        Log::record('Tambah Pelanggan', "Menambahkan akun peminjam baru: {$user->username}");

        return redirect()->back()->with('success', 'Data Pelanggan Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::where('role', 'peminjam')->findOrFail($id);

        $data = $request->validate([
            'username' => 'required',
            'email' => 'required',
            'status' => 'required',
        ]);

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        Log::record('Update Pelanggan', "Memperbarui data akun peminjam: {$user->username}");

        return redirect()->back()->with('success', 'Data Pelanggan Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::where('role', 'peminjam')->findOrFail($id);
        $username = $user->username;
        $user->delete();

        Log::record('Hapus Pelanggan', "Menghapus data akun peminjam: {$username}");

        return redirect()->back()->with('success', 'Data Pelanggan Berhasil Dihapus');
    }
}
