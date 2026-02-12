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
              <h3>Reset Password</h3>
            </div>
        <!-- Pills content -->
         @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif

          @if (session('status'))
              <div class="alert alert-success">
                  {{ session('status') }}
              </div>
          @endif
        <div class="tab-content shadow p-4" style="background-color:white">
            <form method="POST" action="{{ route('password-reset-update') }}">
              @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <label for="">Email</label>
                <input class="form-control mb-4 border border-3" type="email" name="email" required placeholder="masukkan email">
                <label for="">Password</label>
                <input class="form-control mb-4 border border-3" type="password" name="password" required>
                <label for="">Konfirmasi Password</label>
                <input  class="form-control mb-4 border border-3"type="password" name="password_confirmation" required>
              <button type="submit" class="btn w-100 btn-primary btn-block mb-3">Reset Password</button>
          </form>
        </div>
      </div>
      </div>
      </div>
    </section><!-- /Hero Section -->
   </main>
</body>

@include('asset-landing.footer')