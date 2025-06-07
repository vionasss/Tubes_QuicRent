<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vehicles;
use App\Models\Motor;



class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'alamat',
        'tanggal_rental',
        'tanggal_kembali',
        'catatan',
        'motor_id',
        'vehicle_id',
        'nama_kendaraan',
        'harga',
    ];

    public function motor()
    {
        return $this->belongsTo(Motor::class, 'motor_id');
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicles::class, 'vehicle_id');
    }
}
