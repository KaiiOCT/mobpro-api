<?php

namespace App\Http\Controllers;

use App\Models\BangunRuang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BangunRuangController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->header('Authorization');

        if ($userId) {
            $data = BangunRuang::where('email', $userId)
                ->orWhereNull('email')
                ->get()
                ->map(function ($item) use ($userId) {
                    $item->mine = $item->email === $userId ? 1 : 0;
                    return $item;
                });
        } else {
            $data = BangunRuang::whereNull('email')
                ->get()
                ->map(function ($item) {
                    $item->mine = 0;
                    return $item;
                });
        }

        return response()->json($data);
    }


    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $email = $request->header('Authorization'); // <- ambil dari header
//        if($email){
            $request->validate([
                'nama' => 'required|string|max:255',
                'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $path = $request->file('gambar')->store('gambar-bangun-ruang', 'public');

            BangunRuang::create([
                'nama' => $request->nama,
                'gambar' => $path,
                'email' => $email,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil ditambahkan.'
            ]);
//        }
//        return response()->json([
//            'message' => 'Anda Belum Login.'
//        ], 401);
    }

    public function update(Request $request, $id)
    {
        $email = $request->header('Authorization'); // Ambil email dari header

        // Validasi data input
        $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // Cari data berdasarkan id dan email
        $bangunRuang = BangunRuang::where('id', $id)
            ->where('email', $email)
            ->first();

        if (!$bangunRuang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau Anda tidak memiliki akses.'
            ], 404);
        }

        // Update nama
        $bangunRuang->nama = $request->nama;

        // Jika ada gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($bangunRuang->gambar && Storage::disk('public')->exists($bangunRuang->gambar)) {
                Storage::disk('public')->delete($bangunRuang->gambar);
            }

            // Simpan gambar baru
            $path = $request->file('gambar')->store('gambar-bangun-ruang', 'public');
            $bangunRuang->gambar = $path;
        }

        // Simpan perubahan
        $bangunRuang->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $email = $request->header('Authorization'); // Ambil email dari header

        // Cari data berdasarkan id dan email
        $bangunRuang = BangunRuang::where('id', $id)
            ->where('email', $email)
            ->first();

        if (!$bangunRuang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan atau Anda tidak memiliki akses.'
            ], 404);
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
