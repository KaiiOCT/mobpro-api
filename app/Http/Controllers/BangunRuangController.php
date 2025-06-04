<?php

namespace App\Http\Controllers;

use App\Models\BangunRuang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BangunRuangController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('user_id');  // atau bisa pake route param

        if ($userId) {
            $data = BangunRuang::where('user_id', $userId)->get();
        }

        return response()->json($data);
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpg,jpeg,png',
            'user_id' => 'required|string', // atau sesuai tipe user_id (string jika google id)
        ]);

        // Simpan file gambar ke storage public
        $path = $request->file('gambar')->store('gambar-bangun-ruang', 'public');

        // Buat data baru dengan user_id
        $bangunRuang = BangunRuang::create([
            'nama' => $request->nama,
            'gambar' => $path,
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan.',
            'data' => $bangunRuang,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $userId = $request->query('user_id'); // ambil user_id dari query parameter

        if (!$userId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User ID diperlukan untuk menghapus data.'
            ], 400);
        }

        $bangunRuang = BangunRuang::findOrFail($id);

        // Pastikan user_id cocok dengan pemilik data (authorization sederhana)
        if ($bangunRuang->user_id !== $userId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Anda tidak memiliki akses untuk menghapus data ini.'
            ], 403);
        }

        // Hapus file gambar jika ada
        if ($bangunRuang->gambar && Storage::disk('public')->exists($bangunRuang->gambar)) {
            Storage::disk('public')->delete($bangunRuang->gambar);
        }

        // Hapus data dari database
        $bangunRuang->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus.'
        ]);
    }
}
