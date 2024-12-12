<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;  
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MahasiswaExport;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    // Menyimpan data mahasiswa
    public function store(Request $request)
    {
        // Validasi data input
        $validated = $request->validate([
            'npm' => 'required|unique:mahasiswa,npm', // Tabel harus sesuai dengan model (misal: mahasiswa)
            'nama' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
        ], [
            'npm.required' => 'NPM wajib diisi.',
            'npm.unique' => 'NPM sudah terdaftar.',
            'nama.required' => 'Nama wajib diisi.',
            'prodi.required' => 'Program Studi wajib diisi.',
        ]);

        // Menyimpan data mahasiswa yang sudah divalidasi
        $mahasiswa = Mahasiswa::create([
            'npm' => $validated['npm'],
            'nama' => $validated['nama'],
            'prodi' => $validated['prodi'],
        ]);

        // Mengembalikan data mahasiswa yang baru dalam response JSON
        return response()->json([
            'success' => true,
            'data' => $mahasiswa
        ]);
    }

    public function index()
    {
        // Logic untuk mengambil data mahasiswa
        $mahasiswa = Mahasiswa::all();
        return view('mahasiswa.index', compact('mahasiswa'));
    }

    // Menghapus data mahasiswa berdasarkan ID
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return back()->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    // Mengekspor data mahasiswa ke file Excel
    public function export()
    {
        return Excel::download(new MahasiswaExport, 'mahasiswa.xlsx');
    }
}
