  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-light elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('guru.dashboard') }}" class="brand-link">
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
          <a href="#" class="d-block">{{ App\Models\Guru::where('user_id',auth::user()->id)->first()->nama_lengkap }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        
          <li class="nav-item">
            <a href="{{ route('guru.dashboard') }}" class="nav-link @if(Route::is('guru.dashboard')) active @endif">
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

          <li class="nav-item">
            <a href="{{ route('guru.angket.index') }}" class="nav-link @if(Route::is('guru.angket.*', 'guru.soal.*')) active @endif">
               <i class="nav-icon fas fa-file"></i>
              <p>
                Data Angket
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('guru.siswa.index') }}" class="nav-link @if(Route::is('guru.siswa.index')) active @endif">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Data Siswa
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('guru.tanggapan.index') }}" class="nav-link @if(Route::is('guru.tanggapan.*')) active @endif">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Data Tanggapan
              </p>
            </a>
          </li>

          <li class="nav-item @if(Route::is('guru.report', 'guru.report.*')) menu-open @endif">
            <a href="#" class="nav-link @if(Route::is('guru.report.*')) active @endif">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
                Report
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('guru.report') }}" class="nav-link @if(Route::is('guru.report', 'guru.report.korban', 'guru.report.pelaku')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Siswa</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('guru.report.history') }}" class="nav-link @if(Route::is('guru.report.history')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>History</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('guru.report.kelas') }}" class="nav-link @if(Route::is('guru.report.kelas')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Kelas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('guru.report.sosiogram') }}" class="nav-link @if(Route::is('guru.report.sosiogram')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sosiogram</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('guru.report.matriks') }}" class="nav-link @if(Route::is('guru.report.matriks')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Matriks</p>
                </a>
              </li>
            </ul>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>