<?php

namespace App\Http\Controllers;

use App\Models\BangunRuang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BangunRuangController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('email');  // atau bisa pake route param

        if ($userId) {
            $data = BangunRuang::where('email', $userId)->get();
        } else{
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
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('gambar')->store('gambar-bangun-ruang', 'public');

        // Coba berbagai cara mengambil header Authorization
        $email = $request->header('Authorization')
            ?? $request->header('authorization')
            ?? $request->server('HTTP_AUTHORIZATION')
            ?? $request->bearerToken();

        BangunRuang::create([
            'nama' => $request->nama,
            'gambar' => $path,
            'email' => $email,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan.'
        ]);
    }

    public function destroy($id)
    {
        $bangunRuang = BangunRuang::findOrFail($id);

        // Hapus file gambar jika ada
        if ($bangunRuang->gambar && Storage::disk('public')->exists($bangunRuang->gambar)) {
            Storage::disk('public')->delete($bangunRuang->gambar);
        }

        // Hapus data dari database
        $bangunRuang->delete();

        return redirect()->route('show')->with('success', 'Data berhasil dihapus.');
    }

}
