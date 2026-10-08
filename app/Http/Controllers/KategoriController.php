<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    /**
     * Menampilkan daftar semua kategori alat.
     */
    public function index(Request $request)
    {
        $kategoris = Kategori::latest()->paginate(10)->withQueryString();

        return view('Admin.Kategori.index', compact('kategoris'));
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
            'status' => 'required',
        ]);
    
        $kategori = Kategori::create($data);

        Log::record('Tambah Kategori', "Menambahkan kategori baru: {$kategori->nama_kategori}");

        return redirect()->route('admin.kategori.index')->with('succes', 'Data Kategori Berhasil Ditambahkan');
    }

    /**
     * Memperbarui data kategori di database.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
            'status' => 'required',
        ]);

        $kategori->update($data);

        Log::record('Update Kategori', "Memperbarui data kategori: {$kategori->nama_kategori}");

        return redirect()->route('admin.kategori.index')->with('succes', 'Data Kategori Berhasil Diperbarui');
    }

    /**
     * Menghapus data kategori dari database.
     */
    public function destroy(Kategori $kategori)
    {
        if ($kategori->alat()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data alat inventaris.');
        }

        $namaKategori = $kategori->nama_kategori;
        $kategori->delete();

        Log::record('Hapus Kategori', "Menghapus kategori: {$namaKategori}");

        return redirect()->route('admin.kategori.index')->with('succes', 'Data Kategori Berhasil Dihapus');
    }
}
