<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rsetBarangMasuk = BarangMasuk::with('barang')->paginate(10);

        return view('barangmasuk.index', compact('rsetBarangMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $abarang = Barang::all();
        return view('barangmasuk.create', compact('abarang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_masuk'     => 'required|date',
            'qty_masuk'     => 'required|numeric|min:1',
            'barang_id'     => 'required|exists:barang,id',
        ]);
    
        $tgl_masuk = $request->tgl_masuk;
        $barang_id = $request->barang_id;
    
        // Check if there's any BarangKeluar with a date earlier than tgl_masuk
        $existingBarangKeluar = BarangKeluar::where('barang_id', $barang_id)
            ->where('tgl_keluar', '<', $tgl_masuk)
            ->exists();
    
        if ($existingBarangKeluar) {
            return redirect()->back()->withInput()->withErrors(['tgl_masuk' => 'Tanggal masuk tidak boleh melebihi tanggal keluar!']);
        }
    
        BarangMasuk::create([
            'tgl_masuk'  => $tgl_masuk,
            'qty_masuk'  => $request->qty_masuk,
            'barang_id'  => $barang_id,
        ]);
    
        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarangMasuk = BarangMasuk::find($id);

        return view('barangmasuk.show', compact('rsetBarangMasuk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $abarang = Barang::all();
        $rsetBarangMasuk = BarangMasuk::find($id);
        $selectedBarang = Barang::find($rsetBarangMasuk->barang_id);

        return view('barangmasuk.edit', compact('rsetBarangMasuk', 'abarang', 'selectedBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tgl_masuk'     => 'required|date',
            'qty_masuk'     => 'required|numeric|min:1',
            'barang_id'     => 'required|exists:barang,id',
        ]);
    
        $tgl_masuk = $request->tgl_masuk;
        $barang_id = $request->barang_id;
    
        // Check if there's any BarangKeluar with a date earlier than tgl_masuk
        $existingBarangKeluar = BarangKeluar::where('barang_id', $barang_id)
            ->where('tgl_keluar', '<', $tgl_masuk)
            ->exists();
    
        if ($existingBarangKeluar) {
            return redirect()->back()->withInput()->withErrors(['tgl_masuk' => 'Tanggal masuk tidak boleh melebihi tanggal keluar!']);
        }
    
        $barangMasuk = BarangMasuk::findOrFail($id);
    
        $barangMasuk->update([
            'tgl_masuk'  => $tgl_masuk,
            'qty_masuk'  => $request->qty_masuk,
            'barang_id'  => $barang_id,
        ]);
    
        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Diubah!']);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rsetBarangMasuk = BarangMasuk::find($id);
        $rsetBarangMasuk->delete();

        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}