<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        return response()->json(Buku::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'penulis' => 'required|string',
            'tahun_terbit' => 'required|integer',
            'deskripsi' => 'required|string'
        ]);

        $buku = Buku::create($request->all());
        return response()->json($buku, 201);
    }

    public function show(Buku $buku)
    {
        return response()->json($buku);
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul' => 'required|string',
            'penulis' => 'required|string',
            'tahun_terbit' => 'required|integer',
            'deskripsi' => 'required|string'
        ]);

        $buku->update($request->all());
        return response()->json($buku);
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();
        return response()->json(null, 204);
    }
}
