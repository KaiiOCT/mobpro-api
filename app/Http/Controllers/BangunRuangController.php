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
        } else {
            $data = BangunRuang::all();
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
            'user_id' => 'required|exists:users,id',
        ]);

        // Simpan file gambar ke storage
        $path = $request->file('gambar')->store('gambar-bangun-ruang', 'public');

        // Buat data baru di database
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

        // Pastikan user yang menghapus adalah pemilik data
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
