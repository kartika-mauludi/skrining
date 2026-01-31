@extends('siswa.layout.index')

@section('content')

<section class="content">
<div class="container-fluid">
    <div class="row justify-content-center pb-3 pt-3">
      <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Angket</h3>
              </div>
            </div>
        </div>
      <div class="col-md-7">
       <div class="row justify-content-center pb-1">
        <div class="col-md-12">
            <div class="card card-primary">
              <form>
                <div class="card-body">
                  <div class="form-group">
                      <label for="absen">No Urut Absen</label>
                      <select name="" id="" class="form-control form-control-sm w-100 mr-1">
                        <option value=""></option>
                      </select>
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
    <div class="row justify-content-center pb-1">
        <div class="col-md-12">
            <div class="card card-primary">
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <input type="text" class="form-control" id="text" value>
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
     <div class="row justify-content-center pb-1">
        <div class="col-md-12">
            <div class="card card-primary">
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="no_Induk">No Induk</label>
                    <input type="number" readonly class="form-control" id="no_induk" value="">
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
    <div class="row justify-content-center pb-1">
        <div class="col-md-12">
            <div class="card card-primary">
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="Nama">Nama</label>
                    <input type="text" class="form-control" id="nama" readonly value="">
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
    <div class="row justify-content-center pb-1">
        <div class="col-md-12">
            <div class="card card-primary">
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Jenis Kelamin</label>
                     <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio1">
                          <label class="form-check-label">Laki - Laki</label>
                    </div>
                    <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio1">
                          <label class="form-check-label">Perempuan</label>
                    </div>
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
      </div>
      <div class="col-md-4">
           <div class="card card-primary sticky-siswa">
            <div class="card-header">
                <h3 class="card-title">Daftar Siswa</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ( $siswas as $siswa )
                      <tr class="active-siswa">
                            <td>{{ $siswa->id }}</td>
                            <td>Ahmad</td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
</div>

@endsection