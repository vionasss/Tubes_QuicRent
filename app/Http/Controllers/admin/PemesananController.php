<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\vehicles;
use App\Models\Review;
use App\Models\Rental as Rentals;
use App\Models\Motor;

class PemesananController extends Controller
{
    public function index()
    {
        $vehicles = vehicles::all();
        $rentals = Rentals::all();
        $motors = Motor::latest()->get();
        $reviews = Review::latest()->take(3)->get();
        return view('admin.Pemesanan.Data', compact('vehicles', 'reviews', 'motors', 'rentals'));
    }
    public function detail($id)
    {
        $motor = Motor::findOrFail($id);
        return view('admin.Pemesanan.Data', compact('motor'));
    }

    public function detailvehicles($id)
    {
        $vehicles = vehicles::findOrFail($id);
        return view('rentMobil', compact('vehicles'));
    }

    public function rent(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'telepon' => 'required|numeric|digits_between:10,15',
            'alamat' => 'nullable|string|max:500',
            'tanggal_rental' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_rental',
            'catatan' => 'nullable|string|max:1000',
            'nama_kendaraan' => 'nullable|string|max:255',
            'harga' => 'nullable|numeric|min:0',
            'motor_id' => 'nullable|exists:motor,id',
            'mobil_id' => 'nullable|exists:vehicles,id',
        ]);

        Rentals::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'tanggal_rental' => $request->tanggal_rental,
            'tanggal_kembali' => $request->tanggal_kembali,
            'catatan' => $request->catatan,
            'status' => 'unpaid',
            'nama_kendaraan' => $request->nama_kendaraan,
            'harga' => $request->harga,
            'motor_id' => $request->motor_id,
            'mobil_id' => $request->mobil_id,
        ]);

        return back()->with('success', 'Penyewaan berhasil dikirim. Kami akan menghubungi Anda segera.');
    }

    public function showForm()
    {
        return view('admin.Pemesanan.Data'); // ganti dengan nama view kamu
    }

    public function submitReview(Request $request)
    {
        $request->validate([
            'review_text' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Review::create([
            'text' => $request->review_text,
            'rating' => $request->rating,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim.');
    }
    public function create($id)
    {
        $motors = Motor::findOrFail($id); // Ambil data dari database berdasarkan ID
        return view('detail', compact('motor'));
    }

}
