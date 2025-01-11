<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    // Menampilkan semua berita
    public function index()
    {
        $berita = Berita::all();

        if ($berita->isEmpty()) {
            return $this->sendError('No berita found', 404);
        }

        foreach ($berita as $item) {
            $item->gambar = asset('storage/' . $item->gambar);
        }

        return $this->sendResponse($berita, 'Berita retrieved successfully');
    }

    // Menampilkan berita berdasarkan ID
    public function show($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return $this->sendError('Berita not found', 404);
        }

        $berita->gambar = asset('storage/' . $berita->gambar);
        return $this->sendResponse($berita, 'Berita retrieved successfully');
    }

    // Menyimpan berita baru
public function store(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'judul' => 'required|string',
        'deskripsi' => 'required|string',
        'penulis' => 'required|string',
        'gambar' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048'
    ], [
        'judul.required' => 'The title is required.',
        'deskripsi.required' => 'The description is required.',
        'penulis.required' => 'The author is required.',
        'gambar.required' => 'An image is required.',
        'gambar.image' => 'The file must be an image.',
        'gambar.mimes' => 'The image must be a file of type: jpg, jpeg, png, gif.',
        'gambar.max' => 'The image must not be greater than 2MB.'
    ]);

    try {
        // Upload gambar
        $gambarPath = $request->file('gambar')->store('berita_images', 'public');

        // Simpan berita baru ke database
        $berita = Berita::create([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'penulis' => $validated['penulis'],
            'gambar' => $gambarPath
        ]);

        // Mengubah URL gambar menjadi URL yang bisa diakses
        $berita->gambar = asset('storage/' . $berita->gambar);

        // Kembalikan response dengan data berita yang baru dibuat dan status berhasil
        return $this->sendResponse($berita, 'Berita successfully created', 201);

    } catch (\Exception $e) {
        return $this->sendError('Failed to create berita: ' . $e->getMessage(), 500);
    }
}

// Mengupdate berita berdasarkan ID
public function update(Request $request, $id)
{
    $berita = Berita::find($id);

    if (!$berita) {
        return $this->sendError('Berita not found', 404);
    }

    // Validasi input
    $validated = $request->validate([
        'judul' => 'nullable|string',
        'deskripsi' => 'nullable|string',
        'penulis' => 'nullable|string',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
    ]);

    try {
        // Jika ada gambar baru
        if ($request->hasFile('gambar')) {
            // Menghapus gambar lama jika ada
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }

            // Upload gambar baru
            $gambarPath = $request->file('gambar')->store('berita_images', 'public');
            $validated['gambar'] = $gambarPath;
        }

        // Update berita
        $berita->update($validated);

        // Mengubah URL gambar menjadi URL yang bisa diakses
        $berita->gambar = asset('storage/' . $berita->gambar);

        return $this->sendResponse($berita, 'Berita updated successfully');
    } catch (\Exception $e) {
        return $this->sendError('Failed to update berita: ' . $e->getMessage(), 500);
    }
}


    // Menghapus berita berdasarkan ID
    public function destroy($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return $this->sendError('Berita not found', 404);
        }

        try {
            // Menghapus gambar dari storage jika ada
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }

            // Menghapus berita dari database
            $berita->delete();

            return $this->sendResponse([], 'Berita deleted successfully');
        } catch (\Exception $e) {
            return $this->sendError('Failed to delete berita: ' . $e->getMessage(), 500);
        }
    }

    // Helper function for sending success response
    protected function sendResponse($data, $message, $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    // Helper function for sending error response
    protected function sendError($message, $status = 500)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], $status);
    }
}
