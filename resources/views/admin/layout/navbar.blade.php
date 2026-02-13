  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
      <span class="brand-text font-weight-light"></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
     <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 p-2 mb-3 d-flex justify-content-center">
        <div class="info p-0">
          <a href="#" class="d-block m-0">{{ auth::user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        
         <li class="nav-item">
            <a href="{{ url('admin/index') }}" class="nav-link @if(Route::is('admin.index')) active @endif">
               <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-item @if (request()->is(['admin/masteruser','admin/guru','admin/sekolah'])) menu-open @endif">
            <a href="#" class="nav-link @if (request()->is(['admin/masteruser','admin/guru','admin/sekolah'])) active @endif">
              <i class="nav-icon fas fa-solid fa-database"></i>
              <p>
                Master
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('admin/masteruser') }}" class="nav-link @if(Route::is('masteruser.index')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data User</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('guru.index') }}" class="nav-link @if(Route::is('guru.index')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Guru</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.sekolah.index') }}" class="nav-link @if(Route::is('admin.sekolah.index')) active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Data Sekolah</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="{{ url('admin/angket') }}" class="nav-link @if(Route::is(['angket.index','angketsoal.show'])) active @endif">
               <i class="nav-icon fas fa-regular fa-file"></i>
              <p>
                Angket
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('admin/tanggapan') }}" class="nav-link @if(Route::is('tanggapan.index')) active @endif">
               <i class="nav-icon fas fa-sticky-note"></i>
              <p>
                Tanggapan
              </p>
            </a>
          </li>

            <li class="nav-item">
            <a href="{{ url('admin/index') }}" class="nav-link ">
               <i class="nav-icon fas fa-solid fa-chart-line"></i>
              <p>
                Report
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin.log-login') }}" class="nav-link @if(Route::is('admin.log-login')) active @endif">
               <i class="nav-icon fas fa-solid fa-chart-line"></i>
              <p>
                Log Aktivitas
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('admin/pengaturan') }}" class="nav-link  @if(Route::is('admin.profil')) active @endif">
               <i class="nav-icon fas fa-cogs"></i>
              <p>
                Pengaturan
              </p>
            </a>
          </li>
           
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>