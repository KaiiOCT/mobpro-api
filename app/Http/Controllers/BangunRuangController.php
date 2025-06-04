<?php

namespace App\Http\Controllers;

use App\Models\BangunRuang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BangunRuangController extends Controller
{
    public function index()
    {
        $data = BangunRuang::all();
        return response()->json($data);
//        return view('show', compact('data'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        Log::info('Headers: ', $request->headers->all());
        $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('gambar')->store('gambar-bangun-ruang', 'public');
        $email = $request->header('Authorization'); // <- ambil dari header

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
