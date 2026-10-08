<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
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
    
        Kategori::create($data);

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

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('succes', 'Data Kategori Berhasil Dihapus');
    }
}
