<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Session;

class BarangController extends Controller
{
    public function index(Request $request)
{
    $search = $request->query('search');
    if ($search) {
        $rsetBarang = Barang::where('merk', 'like', '%' . $search . '%')
                                ->orWhere('seri', 'like', '%' . $search . '%')
                                ->orWhere('spesifikasi', 'like', '%' . $search . '%')
                                ->orWhere('stok', 'like', '%' . $search . '%')
                                ->orWhere('kategori_id', 'like', '%' . $search . '%')
                                ->orWhereHas('kategori', function($query) use ($search) {
                                    $query->where('deskripsi', 'like', '%' . $search . '%');
                                })
                                ->with('kategori')
                                ->paginate(10); // Use paginate instead of get
    } else {
        $rsetBarang = Barang::with('kategori')->paginate(10); // Use paginate instead of get
    }

    return view('barang.index', compact('rsetBarang'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akategori = Kategori::all();
        return view('barang.create',compact('akategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return $request;
        //validate form
        $request->validate([
            'merk'          => 'required',
            'seri'          => 'required',
            'spesifikasi'   => 'required',
            'stok'          => 'nullable',
            'kategori_id'   => 'required',

        ]);

        DB::beginTransaction(); // Start the transaction
        
        try {
            // Insert a new category using Eloquent
            Barang::create([
                'merk'             => $request->merk,
                'seri'             => $request->seri,
                'spesifikasi'      => $request->spesifikasi,
                'stok'             => $request->stok,
                'kategori_id'      => $request->kategori_id,
                'status'    => 'pending',
            ]);
            
            DB::commit(); // Commit the changes
            
            // Flash success message to the session
            Session::flash('success', 'Barang berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback in case of an exception
            report($e); // Report the exception

            // Flash failure message to the session
            Session::flash('gagal', 'Barang gagal disimpan!');
        }
        //redirect to index
        return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarang = Barang::find($id);

        //return $rsetBarang;

        //return view
        return view('barang.show', compact('rsetBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    $akategori = Kategori::all();
    $rsetBarang = Barang::find($id);
    $selectedKategori = Kategori::find($rsetBarang->kategori_id);

    return view('barang.edit', compact('rsetBarang', 'akategori', 'selectedKategori'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'merk'        => 'required',
            'seri'        => 'required',
            'spesifikasi' => 'required',
            'stok'        => 'nullable',
            'kategori_id' => 'required',
        ]);

        $rsetBarang = Barang::find($id);

            //update post without image
            $rsetBarang->update([
                'merk'          => $request->merk,
                'seri'          => $request->seri,
                'spesifikasi'   => $request->spesifikasi,
                'stok'          => $request->stok,
                'kategori_id'   => $request->kategori_id,
            ]);

        // Redirect to the index page with a success message
        return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Diubah!']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (DB::table('barangmasuk')->where('barang_id', $id)->exists() || DB::table('barangkeluar')->where('barang_id', $id)->exists()) { 
            return redirect()->route('barang.index')->with(['Gagal' => 'Data Sedang Digunakan']);
        } else {
            $rsetBarang = Barang::find($id);
            $rsetBarang->delete();
            return redirect()->route('barang.index')->with(['Success' => 'Data Berhasil dihapus']);
        }
    }
}