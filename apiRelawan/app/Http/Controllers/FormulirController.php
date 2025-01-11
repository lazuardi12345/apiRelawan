<?php

namespace App\Http\Controllers;

use App\Models\Formulir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormulirController extends Controller
{
    public function index()
    {
        return response()->json(Formulir::all(), 200);
    }

    public function show($id)
    {
        $formulir = Formulir::find($id);
        if (!$formulir) {
            return response()->json(['error' => 'Formulir not found'], 404);
        }
        $formulir->ktp = asset('storage/' . $formulir->ktp);
        return response()->json($formulir, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'jenis_kelamin' => 'nullable|in:laki-laki,wanita',
            'tempat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'provinsi' => 'required|string',
            'kabupaten' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'kelurahan' => 'nullable|string',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string',
            'motivasi' => 'nullable|string',
            'kontribusi' => 'nullable|string',
            'ktp' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
    
        // Handle file upload
        $ktpPath = $request->file('ktp')->store('formulir_ktp', 'public');
        $validated['ktp'] = $ktpPath;
    
        // Create the new formulir record
        $formulir = Formulir::create($validated);
    
        // Return the response
        $formulir->ktp = asset('storage/' . $formulir->ktp);
        return response()->json($formulir, 201);
    }
    

    public function update(Request $request, $id)
{
    $formulir = Formulir::find($id);
    if (!$formulir) {
        return response()->json(['error' => 'Formulir not found'], 404);
    }

    // Validate incoming data
    $validated = $request->validate([
        'nama' => 'sometimes|string',
        'jenis_kelamin' => 'nullable|in:laki-laki,wanita',
        'tempat' => 'sometimes|string',
        'tanggal_lahir' => 'sometimes|date',
        'provinsi' => 'sometimes|string',
        'kabupaten' => 'nullable|string',
        'kecamatan' => 'nullable|string',
        'kelurahan' => 'nullable|string',
        'email' => 'nullable|email',
        'no_hp' => 'nullable|string',
        'motivasi' => 'nullable|string',
        'kontribusi' => 'nullable|string',
        'ktp' => 'sometimes|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    // Handle file upload if a new file is provided
    if ($request->hasFile('ktp')) {
        if ($formulir->ktp && Storage::disk('public')->exists($formulir->ktp)) {
            Storage::disk('public')->delete($formulir->ktp);
        }
        $ktpPath = $request->file('ktp')->store('formulir_ktp', 'public');
        $validated['ktp'] = $ktpPath;
    }

    // Only update the fields that were validated
    $formulir->update(array_filter($validated));

    // Return the updated response
    $formulir->ktp = asset('storage/' . $formulir->ktp);
    return response()->json($formulir, 200);
}

    public function destroy($id)
    {
        $formulir = Formulir::find($id);
        if (!$formulir) {
            return response()->json(['error' => 'Formulir not found'], 404);
        }

        if ($formulir->ktp && Storage::disk('public')->exists($formulir->ktp)) {
            Storage::disk('public')->delete($formulir->ktp);
        }

        $formulir->delete();

        return response()->json(['message' => 'Formulir deleted successfully'], 200);
    }
}
