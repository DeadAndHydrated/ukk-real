<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rsetBarangKeluar = BarangKeluar::with('barang')->paginate(10);

        return view('barangkeluar.index', compact('rsetBarangKeluar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $abarang = Barang::all();
        return view('barangkeluar.create',compact('abarang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_keluar'   => 'required|date',
            'qty_keluar'   => 'required|numeric|min:1',
            'barang_id'    => 'required|exists:barang,id',
        ]);
    
        $tgl_keluar = $request->tgl_keluar;
        $barang_id = $request->barang_id;
    
        // Check if there's any BarangMasuk with a date later than tgl_keluar
        $existingBarangMasuk = BarangMasuk::where('barang_id', $barang_id)
            ->where('tgl_masuk', '>', $tgl_keluar)
            ->exists();
    
        if ($existingBarangMasuk) {
            return redirect()->back()->withInput()->withErrors(['tgl_keluar' => 'Tanggal keluar tidak boleh mendahului tanggal masuk!']);
        }
    
        $barang = Barang::find($barang_id);
    
        if ($request->qty_keluar > $barang->stok) {
            return redirect()->back()->withInput()->withErrors(['qty_keluar' => 'Jumlah barang keluar melebihi stok!']);
        }
    
        BarangKeluar::create([
            'tgl_keluar'  => $tgl_keluar,
            'qty_keluar'  => $request->qty_keluar,
            'barang_id'   => $barang_id,
        ]);
    
        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarangKeluar = BarangKeluar::find($id);

        //return $rsetBarang;

        //return view
        return view('barangkeluar.show', compact('rsetBarangKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $abarang = Barang::all();
        $rsetBarangKeluar = BarangKeluar::find($id);
        $selectedBarang = Barang::find($rsetBarangKeluar->barang_id);
    
        return view('barangkeluar.edit', compact('rsetBarangKeluar', 'abarang', 'selectedBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tgl_keluar'   => 'required|date',
            'qty_keluar'   => 'required|numeric|min:1',
            'barang_id'    => 'required|exists:barang,id',
        ]);
    
        $tgl_keluar = $request->tgl_keluar;
        $barang_id = $request->barang_id;
    
        // Check if there's any BarangMasuk with a date later than tgl_keluar
        $existingBarangMasuk = BarangMasuk::where('barang_id', $barang_id)
            ->where('tgl_masuk', '>', $tgl_keluar)
            ->exists();
    
        if ($existingBarangMasuk) {
            return redirect()->back()->withInput()->withErrors(['tgl_keluar' => 'Tanggal keluar tidak boleh mendahului tanggal masuk!']);
        }
    
        $barang = Barang::find($barang_id);
    
        if ($request->qty_keluar > $barang->stok) {
            return redirect()->back()->withInput()->withErrors(['qty_keluar' => 'Jumlah barang keluar melebihi stok!']);
        }
    
        $barangKeluar = BarangKeluar::findOrFail($id);
    
        $barangKeluar->update([
            'tgl_keluar'  => $tgl_keluar,
            'qty_keluar'  => $request->qty_keluar,
            'barang_id'   => $barang_id,
        ]);
    
        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rsetBarangKeluar = BarangKeluar::find($id);

        //delete post
        $rsetBarangKeluar->delete();

        //redirect to index
        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}