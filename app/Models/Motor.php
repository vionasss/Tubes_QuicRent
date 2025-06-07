<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class motor extends Model
{
    use HasFactory;
    protected $table = 'motor';
    protected $fillable = [
        'nama_kendaraan',
        'harga',
        'gambar',
    ];
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
