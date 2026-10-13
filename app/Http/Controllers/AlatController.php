<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alats = Alat::latest()->paginate(10);
        $kategoris = Kategori::all();

        return view('Admin.Alat.index', compact('alats', 'kategoris'));
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
            'id_kategori' => 'required',
            'kode_alat' => 'required',
            'nama_alat' => 'required',
            'deskripsi' => 'nullable',
            'harga_sewa' => 'required',
            'stok_total' => 'required',
            'stok_tersedia' => 'nullable',
            'stok_dipinjam' => 'nullable',
            'stok_rusak' => 'nullable',
            'nilai_penggantian' => 'required',
            'foto' => 'nullable',
            'status' => 'required',
        ]);

        $data['stok_tersedia'] = $data['stok_tersedia'] ?? $data['stok_total'];
        $data['stok_dipinjam'] = $data['stok_dipinjam'] ?? 0;
        $data['stok_rusak'] = $data['stok_rusak'] ?? 0;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('alat', 'public');
        }

        $alat = Alat::create($data);

        Log::record('Tambah Alat', "Menambahkan alat inventaris baru: {$alat->nama_alat} ({$alat->kode_alat})");

        return redirect()->back()->with('success', 'Data Alat Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alat $alat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alat $alat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alat $alat)
    {
        $data = $request->validate([
            'id_kategori' => 'required',
            'kode_alat' => 'required',
            'nama_alat' => 'required',
            'deskripsi' => 'nullable',
            'harga_sewa' => 'required',
            'stok_total' => 'required',
            'stok_tersedia' => 'required',
            'stok_dipinjam' => 'nullable',
            'stok_rusak' => 'nullable',
            'nilai_penggantian' => 'required',
            'foto' => 'nullable',
            'status' => 'required',
        ]);

        $data['stok_dipinjam'] = $data['stok_dipinjam'] ?? 0;
        $data['stok_rusak'] = $data['stok_rusak'] ?? 0;

        if ($request->hasFile('foto')) {
            if ($alat->foto && Storage::disk('public')->exists($alat->foto)) {
                Storage::disk('public')->delete($alat->foto);
            }
            $data['foto'] = $request->file('foto')->store('alat', 'public');
        } else {
            unset($data['foto']);
        }

        $alat->update($data);

        Log::record('Update Alat', "Memperbarui data alat inventaris: {$alat->nama_alat} ({$alat->kode_alat})");

        return redirect()->back()->with('success', 'Data Alat Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alat $alat)
    {
        $namaAlat = $alat->nama_alat;
        $kodeAlat = $alat->kode_alat;

        if ($alat->foto && Storage::disk('public')->exists($alat->foto)) {
            Storage::disk('public')->delete($alat->foto);
        }

        $alat->delete();

        Log::record('Hapus Alat', "Menghapus alat inventaris: {$namaAlat} ({$kodeAlat})");

        return redirect()->back()->with('success', 'Data Alat Berhasil Dihapus');
    }
}
