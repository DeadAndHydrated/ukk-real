@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2>EDIT BARANG MASUK</h2>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('barangmasuk.update', $rsetBarangMasuk->id) }}" method="POST" enctype="multipart/form-data">                    
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="font-weight-bold">TANGGAL MASUK</label>
                                <input type="date" class="form-control @error('tgl_masuk') is-invalid @enderror" name="tgl_masuk" value="{{ $rsetBarangMasuk->tgl_masuk }}" placeholder="Masukkan Tanggal Masuk">
                           
                                @error('tgl_masuk')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">QTY MASUK</label>
                                <input type="number" min="0" class="form-control @error('qty_masuk') is-invalid @enderror" name="qty_masuk" value="{{ $rsetBarangMasuk->qty_masuk }}" placeholder="Masukkan qty masuk">
                           
                                @error('qty_masuk')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">BARANG</label>
                                <select class="form-control" name="barang_id" aria-label="Default select example">
                                    @foreach ($abarang as $barang)
                                        <option value="{{ $barang->id }}" {{ $selectedBarang->id == $barang->id ? 'selected' : '' }}>{{ $barang->id }}</option>
                                    @endforeach
                                </select>
                            
                                @error('barang_id')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-md btn-primary">SIMPAN</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
