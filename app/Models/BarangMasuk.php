<?php

namespace App\Models;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;
    protected $table = 'barangmasuk';

    protected $fillable = [
        'tgl_masuk', 'qty_masuk', 'barang_id'
    ];

    // Define the relationship with Kategori
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Define the relationship with BarangMasuk
    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'barang_id');
    }

}