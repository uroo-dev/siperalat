<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;

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

        Alat::create($data);
        return redirect()->back()->with('success', 'Data Alat Berhasil Di Tambahkan');
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

        $alat->update($data);

        return redirect()->back()->with('success', 'Data Alat Berhail Di Ubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alat $alat)
    {
        $alat->delete();

        return redirect()->back()->with('success', 'Data Alat Berhasil Dihapus');
    }
}
