@extends('guru.layout.index')

@section('content')
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Profil</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Profil</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       
 <div class="row justify-content-center">
          <!-- left column -->
          <div class="col-md-8">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Profil Guru</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
               @if(session('error'))
                  <script src="{{ asset('admin/assets-admin/dist/js/sweetalert.min.js') }}"></script>
                  <script>
                      Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: "{{ session('error') }}"
                      });
                  </script>
                @endif
                 @if(session('success'))
                  <script src="{{ asset('admin/assets-admin/dist/js/sweetalert.min.js') }}"></script>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: "{{ session('success') }}"
                        });
                    </script>
                  @endif
              <form method="POST" action="{{ route('guru.profil.update') }}">
                @csrf
                <input type="hidden" name="id" value="{{ auth::user()->id }}">
                <div class="card-body">
                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input type="text" class="form-control" id="nip" name="nip" value="{{ $profil->guru->nip }}" readonly>
                  </div>
                 <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ $profil->guru->nama_lengkap }}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" name="" readonly value="{{ $profil->email }}">
                  </div>
                  <div class="form-group">
                    <label for="no_tlp">Nomor WA</label>
                    <input type="text" class="form-control" id="no_tlp" name="no_tlp" value="{{ $profil->guru->no_tlp }}">
                  </div>
                  <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $profil->guru->alamat }}">
                  </div>
                  <div class="form-group">
                    <label for="tempat_lahir">Tempat Lahir</label>
                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ $profil->guru->tempat_lahir }}">
                  </div>
                  <div class="form-group">
                    <label for="tgl_lahir">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="{{ $profil->guru->tgl_lahir }}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                    <small>Jika tidak ingin mengubah biarkan kosong</small>
                  </div>
                  </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </form>
            </div>
            </div>
    </section>
    <!-- /.content -->
  </div>

  @endsection
