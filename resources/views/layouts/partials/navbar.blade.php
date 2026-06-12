<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
    </ul>
    
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
        
        <!-- ======================================================== -->
        <!-- SCHOOL SWITCHER (HANYA MUNCUL UNTUK SUPER ADMIN) -->
        <!-- ======================================================== -->
        @if(Auth::check() && Auth::user()->isSuperAdmin())
        <li class="nav-item d-none d-md-block me-4 border-end pe-4">
            <form action="{{ route('superadmin.switch_school') }}" method="POST" id="formSwitchSchool" class="d-flex align-items-center gap-2">
                @csrf
                <iconify-icon icon="solar:buildings-bold-duotone" class="fs-6 text-primary"></iconify-icon>
                <select name="active_school_id" class="form-select form-select-sm border-primary text-primary fw-bold shadow-sm" onchange="document.getElementById('formSwitchSchool').submit()" style="min-width: 200px;">
                    <option value="">-- Mode Pusat (Semua Data) --</option>
                    @foreach(\App\Models\School::where('status', 'aktif')->get() as $school)
                        <option value="{{ $school->id }}" {{ session('active_school_id') == $school->id ? 'selected' : '' }}>
                            Pantau: {{ $school->nama_sekolah }}
                        </option>
                    @endforeach
                </select>
            </form>
        </li>
        @endif

        <!-- Dropdown Profil -->
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon-hover p-0 ms-3" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('template2/images/profile/user-1.jpg') }}" alt="Profile" width="40" height="40" class="rounded-circle border border-2 border-white shadow-sm">
          </a>
          
          <!-- Menu Dropdown dengan UI Modern -->
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up border-0 shadow-sm rounded-4 mt-2" aria-labelledby="drop2" style="min-width: 240px;">
            <div class="message-body">
              
              <!-- Info User di dalam dropdown -->
              <div class="d-flex align-items-center px-4 py-3 border-bottom" style="border-color: #f3f4f6;">
                <img src="{{ asset('template2/images/profile/user-1.jpg') }}" alt="Profile" width="45" height="45" class="rounded-circle object-fit-cover me-3 shadow-sm">
                <div>
                  <h6 class="mb-0 fs-5 fw-semibold text-dark">{{ Auth::user()->name ?? 'Administrator' }}</h6>
                  <p class="mb-0 text-muted fw-medium text-capitalize small">{{ Auth::user()->role ?? 'admin' }}</p>
                </div>
              </div>
              
              <!-- Form Logout Real ke Backend Laravel -->
              <div class="px-4 py-3">
                <form action="/logout" method="POST" class="d-block m-0">
                  @csrf
                  <button type="submit" class="btn btn-outline-danger fw-medium rounded-pill w-100 shadow-none d-flex align-items-center justify-content-center gap-2">
                    <iconify-icon icon="solar:logout-2-bold-duotone" class="fs-5"></iconify-icon> Keluar
                  </button>
                </form>
              </div>

            </div>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</header>