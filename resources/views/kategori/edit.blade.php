@extends('layouts.adm-main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2>EDIT KATEGORI</h2>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('kategori.update',$rsetKategori->id) }}" method="POST" enctype="multipart/form-data">                    
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="font-weight-bold">DESKRIPSI</label>
                                <input type="text" class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" value="{{ old('deskripsi',$rsetKategori->deskripsi) }}" placeholder="Masukkan Merk Barang">
                           
                                <!-- error message untuk merk -->
                                @error('deskripsi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">KATEGORI</label>
                                <!-- <select class="form-control" name="kategori_id" aria-label="Default select example"> -->
                                <select class="form-control @error('kategori') is-invalid @enderror" name="kategori" aria-label="Default select example">
                                    <option value="A" {{ (old('kategori', $rsetKategori->kategori) == 'A') ? 'selected' : '' }}>A</option>
                                    <option value="M" {{ (old('kategori', $rsetKategori->kategori) == 'M') ? 'selected' : '' }}>M</option>
                                    <option value="BHP" {{ (old('kategori', $rsetKategori->kategori) == 'BHP') ? 'selected' : '' }}>BHP</option>
                                    <option value="BTHP" {{ (old('kategori', $rsetKategori->kategori) == 'BTHP') ? 'selected' : '' }}>BTHP</option>
                                </select>
                            
                                <!-- error message untuk kategori -->
                                @error('kategori')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-md btn-primary">SIMPAN</button>
                            <button type="reset" class="btn btn-md btn-warning">RESET</button>

            </div>
        </div>
    </div>
@endsection