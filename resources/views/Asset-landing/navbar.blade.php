 <header id="header" class="header d-flex align-items-center fixed-top" style="background-image:
      linear-gradient(rgba(34, 157, 128, 0.8))">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img class="logo-img" src="{{ asset('assets-landing/img/logo/Logo-SDGs.png') }}" alt=""> -->
        <h1 class="sitename" style="color:white">SKRINING</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home<br></a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#portfolio">Portfolio</a></li>
          <li><a href="#team">Team</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      @guest
        <a class="btn-getstarted flex-md-shrink-0" href="{{ url('/login') }}">Login</a>
      @endguest
      @auth
        @if(auth()->user()->role == 'super_admin')
          <a class="btn-getstarted flex-md-shrink-0" href="{{ route('admin.index') }}">Dashboard</a>
        @elseif(auth()->user()->role == 'guru')
        <a class="btn-getstarted flex-md-shrink-0" href="{{ route('guru.dashboard') }}">Dashboard</a>
        @endif
      @endauth

    </div>
  </header>