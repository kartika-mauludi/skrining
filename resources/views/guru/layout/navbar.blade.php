  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-light elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      {{-- <img src="" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
      <span class="brand-text font-weight-light">Skrining</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ auth()->user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        
          <li class="nav-item">
            <a href="{{ route('guru.index') }}" class="nav-link @if(Route::is('guru.index')) active @endif">
               <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('guru.sekolah.index') }}" class="nav-link @if(Route::is('guru.sekolah.index', 'guru.kelas.index')) active @endif">
               <i class="nav-icon fas fa-building"></i>
              <p>
                Data Sekolah
              </p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>