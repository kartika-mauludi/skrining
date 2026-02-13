@include('Asset-landing.head')
<body class="index-page">
 <!-- navbar  -->
 @include('Asset-landing.navbar')
   <script src="{{ asset('admin/assets-admin/dist/js/sweetalert.min.js') }}"></script>

  <main class="main">
        <!-- Hero Section -->
    <section id="hero" class="hero section">
      <div class="container">
        <div class="row gy-4 justify-content-center">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <div class="py-5 text-center">
              <h3>Registrasi ke Sistem Skrining</h3>
            </div>
        <!-- Pills content -->
        <div class="tab-content shadow p-4" style="background-color:white">
          <form method="POST" action="{{ route('register') }}">
                @csrf

            @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: `{!! implode('<br>', $errors->all()) !!}`
                });
            </script>
            @endif
            
            @if(session('success'))
              <script>
                  Swal.fire({
                      icon: 'success',
                      title: 'Registrasi Berhasil',
                      text: "{{ session('success') }}"
                  }).then((result) => {
                  if (result.isConfirmed) {
                      window.location.href = "{{ route('login') }}";
                  }
              });
              </script>
            @endif
            <div data-mdb-input-init class="form-outline mb-4">
              <label for=""> NIP* </label>
              <input type="text" name="nip" id="NIP" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" required oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Masukkan NIP">
                      @error('nip')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
            </div>
            <div data-mdb-input-init class="form-outline mb-4">
                <label for=""> Username* </label>
                <input type="text" name="name" id="Username" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Masukkan Username">
            </div>
             <!-- Password input -->
            <label class="form-label" for="loginPassword">Password*</label>
            <div data-mdb-input-init class="form-outline mb-3 input-group">
                <input type="password" id="password" name="password" required class="form-control @error('password')is-invalid @enderror password" autocomplete="new-password" placeholder="*******"/>
                 <button class="btn btn-outline-secondary togglePassword" type="button">
                    <i class="fas fa-eye toggleIcon" id="toggleIcon"></i>
                </button>
                 @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <label for="password-confirm" class="form-label">{{ __('Confirm Password*') }}</label>
            <div data-mdb-input-init class="form-outline mb-3 input-group">
                 <input type="password" id="password-confirm" name="password_confirmation" required class="form-control password" autocomplete="new-password" placeholder="*******"/>
                 <button class="btn btn-outline-secondary togglePassword" type="button">
                    <i class="fas fa-eye toggleIcon"></i>
                </button> 
            </div>
             <div data-mdb-input-init class="form-outline mb-4">
                    <label for=""> Nama Lengkap* </label>
                    <input type="text" name="nama_lengkap" id="Name" class="form-control" value="{{ old( 'nama_lengkap') }}" required placeholder="Masukkan Nama Lengkap">
            </div>
             <div data-mdb-input-init class="form-outline mb-4">
                    <label for="">Email*</label>
                    <input type="email" name="email" id="Email" class="form-control @error('email') is-invalid @enderror" value="{{ old( 'email') }}" required placeholder="Masukkan Email">
                         @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
            </div>
             <div data-mdb-input-init class="form-outline mb-4">
                    <label for="">Alamat</label>
                    <input type="text" name="alamat" id="Alamat" class="form-control" value="{{ old('alamat') }}" placeholder="Masukkan Alamat">
            </div>
             <div data-mdb-input-init class="form-outline mb-4">
             <label for="">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}" placeholder="Masukkan Tempat Lahir (opsional)">

            </div>
             <div data-mdb-input-init class="form-outline mb-4">
            <label for="">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" id="TglLahir" class="form-control" value="{{ old('tgl_lahir') }}" placeholder="Masukkan Tanggal Lahir (opsional)">

            </div>
            <div data-mdb-input-init class="form-outline mb-4">
               <label for="">No Wa</label>
               <input type="text" name="no_tlp" id="NoTlp" class="form-control" value="{{ old('no_tlp') }}" oninput="this.value = this.value.replace(/\D/g, '+')" placeholder="Masukkan No WA"> 
            </div>
            <!-- Submit button -->
             <div class="d-grid mx-auto">
               <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn w-100 btn-primary btn-block mb-3"> Register </button>
            </div>
            <!-- Register buttons -->
            <div class="text-center">
                <p>Sudah Punya Akun? <a href="{{ route('login') }}">Login</a></p>
            </div>
            </form>
        </div>
        <!-- Pills content -->
          </div>
          <!-- <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out"> -->
            <!-- <img src="assets-landing/img/hero-img.png" class="img-fluid animated" alt=""> -->
          <!-- </div> -->
        </div>
      </div>
    </section><!-- /Hero Section -->
   </main>
</body>

@include('asset-landing.footer')