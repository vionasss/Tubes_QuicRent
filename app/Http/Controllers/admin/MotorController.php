<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\motor as Motor;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class MotorController extends Controller
{
    public function index()
    {
        $motors = Motor::latest()->get();
        return view('admin.Motor.Data', compact('motors'));
    }

    public function create()
    {
        return view('admin.Motor.MotorCreate');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kendaraan' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $gambarPath = $request->file('gambar')->store('motors', 'public');

        Motor::create([
            'nama_kendaraan' => $request->nama_kendaraan,
            'harga' => $request->harga,
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.motors.index')->with('success', 'Data motor berhasil ditambahkan.');
    }

    public function show(Motor $motor)
    {
        return view('admin.Motor.Show', compact('motor'));
    }

    public function edit(Motor $motor)
    {
        return view('admin.Motor.Edit', compact('motor'));
    }

    public function update(Request $request, Motor $motor)
    {
        $request->validate([
            'nama_kendaraan' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['nama_kendaraan', 'harga']);

        if ($request->hasFile('gambar')) {
            if ($motor->gambar) {
                Storage::disk('public')->delete($motor->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('motors', 'public');
        }

        $motor->update($data);

        return redirect()->route('admin.motors.index')->with('success', 'Data motor berhasil diperbarui.');
    }

    public function destroy(Motor $motor)
    {
        if ($motor->gambar) {
            Storage::disk('public')->delete($motor->gambar);
        }

        $motor->delete();
        return redirect()->route('admin.motors.index')->with('success', 'Data motor berhasil dihapus.');
    }
    
}
