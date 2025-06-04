<?php

namespace App\Http\Controllers;

use App\Models\BangunRuang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BangunRuangController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('userId'); // ambil userId dari query param

        if (!$userId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User ID diperlukan.'
            ], 400);
        }

        // Ambil data yang user_id nya sama dengan $userId
        $data = BangunRuang::where('user_id', $userId)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'user_id' => 'required|exists:users,id',  // pastikan user_id valid
        ]);

        $path = $request->file('gambar')->store('gambar-bangun-ruang', 'public');

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
        $userId = $request->query('userId');
        if (!$userId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User ID diperlukan.'
            ], 400);
        }

        $bangunRuang = BangunRuang::findOrFail($id);

        // Cek apakah data ini milik user yang request (authorization sederhana)
        if ($bangunRuang->user_id != $userId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Tidak punya akses untuk menghapus data ini.'
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
