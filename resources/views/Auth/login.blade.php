@include('Asset-landing.head')
<body class="index-page">
 <!-- navbar  -->
 @include('Asset-landing.navbar')
  <main class="main">
        <!-- Hero Section -->
    <section id="hero" class="hero section">
      <div class="container">
        <div class="row gy-4 justify-content-center">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <div class="py-5 text-center">
              <h3>Login ke Sistem Skrining</h3>
            </div>
        <!-- Pills content -->
        <div class="tab-content shadow p-4" style="background-color:white">
           
            <form method="POST" action="{{ route('login') }}">
                @csrf
            <!-- Email input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <label class="form-label" for="email">Email atau NIP</label>
                <input type="text" id="login" name="login" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" required  oninvalid="this.setCustomValidity('Email / NIP Harus di Isi')" oninput="this.setCustomValidity('')" placeholder="Isi email atau NIP" />
                 @error('login')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <!-- Password input -->
            <label class="form-label"  for="loginPassword">Password</label>
            <div data-mdb-input-init class="form-outline mb-3 input-group">
                <input type="password" id="password" name="password" required class="form-control @error('password')is-invalid @enderror
              " placeholder="*******"/>
                 <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </button>

                 @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <!-- 2 column grid layout -->
            <div class="row mb-4">
                <div class="col-md-6 d-flex justify-content-center">
                <!-- Checkbox -->
                    <div class="form-check mb-3 mb-md-0">
                        <input class="form-check-input" type="checkbox" value="" id="loginCheck" checked />
                        <label class="form-check-label" for="loginCheck"> Remember me </label>
                    </div>
                </div>

                <div class="col-md-6 d-flex justify-content-center">
                <!-- Simple link -->
                <a href="{{ route('forgot-password') }}">Lupa password?</a>
                </div>
            </div>

            <!-- Submit button -->
             <div class="d-grid mx-auto">
               <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn w-100 btn-primary btn-block mb-3"> Login </button>
            </div>
            <!-- Register buttons -->
            <div class="text-center">
                <p>Bukan Member ? <a href="{{ route('register') }}">Registrasi</a></p>
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