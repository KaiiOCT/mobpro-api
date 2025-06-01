<?php

namespace App\Http\Controllers;

use App\Models\BangunRuang;
use Illuminate\Http\Request;

class BangunRuangController extends Controller
{
    public function index()
    {
        $data = BangunRuang::all();
        return response()->json($data);
    //    return view('show', compact('data'));

        // $data = BangunRuang::all()->map(function ($item) {
        //     return [
        //         'id' => $item->id,
        //         'nama' => $item->nama,
        //         'gambar' => asset('storage/' . $item->gambar), // ini akan jadi URL lengkap
        //     ];
        // });

        // return response()->json($data);
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

        BangunRuang::create([
            'nama' => $request->nama,
            'gambar' => $path,
        ]);

        return redirect()->route('show')->with('success', 'Data berhasil ditambahkan.');
    }
}
