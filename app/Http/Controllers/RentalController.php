<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\vehicles;
use App\Models\Review;
use App\Models\Rental;
use App\Models\Motor;

class RentalController extends Controller
{
    public function index()
    {
        $vehicles = vehicles::all();
        $motors = Motor::latest()->get();
        $reviews = Review::latest()->take(3)->get();
        return view('home', compact('vehicles', 'reviews', 'motors'));
    }
    public function detail($id)
    {
        $motor = Motor::findOrFail($id);
        return view('detail', compact('motor'));
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
            'motor_id' => 'nullable|exists:motor,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
        ]);

        // Ambil data kendaraan dari motor atau vehicle
        $nama_kendaraan = null;
        $harga = null;

        // Ambil ID terakhir yang ada
        $lastId = Rental::max('id') ?? 0;
        $newId = $lastId + 1;

        if ($request->motor_id) {
            $motor = Motor::find($request->motor_id);
            $nama_kendaraan = $motor?->nama_kendaraan;
            $harga = $motor?->harga;
        } elseif ($request->vehicle_id) {
            $vehicle = Vehicles::find($request->vehicle_id);
            $nama_kendaraan = $vehicle?->nama_kendaraan;
            $harga = $vehicle?->harga;
        }

        Rental::create([
            'id' => $newId,
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'tanggal_rental' => $request->tanggal_rental,
            'tanggal_kembali' => $request->tanggal_kembali,
            'catatan' => $request->catatan,
            'motor_id' => $request->motor_id,
            'vehicle_id' => $request->vehicle_id,
            'nama_kendaraan' => $nama_kendaraan,
            'harga' => $harga,
        ]);

        return back()->with('success', 'Penyewaan berhasil dikirim.');
    }


    public function showForm()
    {
        return view('detail'); // ganti dengan nama view kamu
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
