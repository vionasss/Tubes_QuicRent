<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\vehicles as vehicle; 
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = vehicle::latest()->get();
        return view('admin.Mobil.Data', compact('vehicles'));
    }

    public function create()
    {
        return view('admin.Mobil.MobilCreate');

    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kendaraan' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $gambarPath = $request->file('gambar')->store('vehicles', 'public');

        vehicle::create([
            'nama_kendaraan' => $request->nama_kendaraan,
            'harga' => $request->harga,
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.vehicles.index')->with('success', 'Data kendaraan berhasil ditambahkan.');
    }

    public function show(vehicle $vehicle)
    {
        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(vehicle $vehicle)
    {
        $vehicle = vehicle::findOrFail($vehicle->id);
        return view('admin.Mobil.Edit', compact('vehicle'));
    }

    public function update(Request $request, vehicle $vehicle)
    {
        $request->validate([
            'nama_kendaraan' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['nama_kendaraan', 'harga']);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($vehicle->gambar) {
                Storage::disk('public')->delete($vehicle->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('vehicles', 'public');
        }

        $vehicle->update($data);

        return redirect()->route('admin.vehicles.index')->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(vehicle $vehicle)
    {
        if ($vehicle->gambar) {
            Storage::disk('public')->delete($vehicle->gambar);
        }

        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')->with('success', 'Data kendaraan berhasil dihapus.');
    }
}
