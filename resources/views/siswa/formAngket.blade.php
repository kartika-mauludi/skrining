@extends('siswa.layout.index')

@section('content')

<section class="content">
<div class="container-fluid">
    <div class="row justify-content-center pb-3 pt-3">
        <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Angket</h3>
              </div>
            </div>
        </div>
    </div>
     <div class="row justify-content-center pb-1">
        <div class="col-md-6">
            <div class="card card-primary">
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">No Urut Absen</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
    <div class="row justify-content-center pb-1">
        <div class="col-md-6">
            <div class="card card-primary">
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Kelas</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
     <div class="row justify-content-center pb-1">
        <div class="col-md-6">
            <div class="card card-primary">
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">No Induk</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
    <div class="row justify-content-center pb-1">
        <div class="col-md-6">
            <div class="card card-primary">
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nama</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
    <div class="row justify-content-center pb-1">
        <div class="col-md-6">
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

@endsection