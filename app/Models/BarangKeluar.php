<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;
    protected $table ='barangkeluar';

    protected $fillable = [
        'tgl_keluar',
        'qty_keluar',
        'barang_id',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Define the relationship with BarangMasuk
    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'barang_id');
    }


}