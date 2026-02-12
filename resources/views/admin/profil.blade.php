@extends('admin.layout.index')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Pengaturan</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       
 <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Profil Admin</h3>
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
              <form method="POST" action="{{ route('admin.pengaturan') }}">
                @csrf
                <input type="hidden" name="jenis" value="profil">
                <input type="hidden" name="id" value="{{ $profil->id }}">
                <div class="card-body">
                 <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="name" value="{{ $profil->name }}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" name="email" value="{{ $profil->email }}">
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
            
        <div class="col-md-6">
            <!-- Form Element sizes -->
          <form method="POST" action="{{ route('admin.pengaturan') }}">
            @csrf
            <input type="hidden" name="jenis" value="pengaturan">
            <input type="hidden" name="id" value="{{ $pengaturan?->id }}">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Setting Whatsapp</h3>
              </div>
              <div class="card-body">
                <label for="">No WhatsApp</label>
                <input class="form-control form-control-md" type="text" name="no_tlp" value="{{ $pengaturan->no_tlp ?? '-' }}">
              </div>
              <!-- /.card-body -->
                 <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
            </form>
            <!-- /.card -->
          <form method="POST" action="{{ route('admin.pengaturan') }}">
            @csrf
             <input type="hidden" name="jenis" value="pengaturan">
            <input type="hidden" name="id" value="{{ $pengaturan?->id }}">
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Setting Email</h3>
              </div>
              <div class="card-body">
                <label for="">Email</label>
                <input class="form-control form-control-md" type="email" name="email" value="{{ $pengaturan->email ?? '-' }}">
              </div>
              <!-- /.card-body -->
                 <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
            <!-- /.card -->
        </form>
    </section>
    <!-- /.content -->
  </div>

@endsection

