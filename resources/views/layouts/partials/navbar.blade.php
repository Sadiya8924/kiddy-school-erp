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

        <!-- ======================================================== -->
        <!-- NOTIFICATION BELL (REAL-TIME NOTIFICATIONS) -->
        <!-- ======================================================== -->
        <li class="nav-item dropdown me-2">
            <a class="nav-link nav-icon-hover position-relative p-0 d-flex align-items-center justify-content-center" href="javascript:void(0)" id="dropNotification" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px;">
                <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-6 text-dark"></iconify-icon>
                @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.25em 0.4em;">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
            
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up border-0 shadow-sm rounded-4 mt-2 p-0" aria-labelledby="dropNotification" style="min-width: 320px; width: 350px;">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-primary text-white rounded-top-4">
                    <h6 class="mb-0 text-white fw-bold">Notifikasi</h6>
                    @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                        <span class="badge bg-white text-primary rounded-pill">{{ Auth::user()->unreadNotifications->count() }} Baru</span>
                    @endif
                </div>
                <div class="message-body" style="max-height: 300px; overflow-y: auto;">
                    @if(Auth::check() && Auth::user()->notifications->count() > 0)
                        @foreach(Auth::user()->unreadNotifications->take(5) as $notification)
                            <a href="#" class="py-3 px-4 d-flex align-items-start border-bottom text-decoration-none {{ $notification->read_at ? 'bg-light' : '' }}">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                                    <iconify-icon icon="solar:bell-bold-duotone" class="fs-5"></iconify-icon>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-dark fw-bold" style="font-size: 0.85rem;">Pemberitahuan Baru</h6>
                                    <span class="d-block text-muted small" style="font-size: 0.75rem;">{{ $notification->data['message'] ?? 'Ada pesan sistem untuk Anda.' }}</span>
                                    <span class="d-block text-muted small mt-1" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <iconify-icon icon="solar:bell-cross-bold-duotone" class="fs-1 text-muted opacity-50 mb-2"></iconify-icon>
                            <p class="text-muted small mb-0">Belum ada notifikasi baru.</p>
                        </div>
                    @endif
                </div>
                <div class="p-2 border-top text-center bg-light rounded-bottom-4">
                    <a href="#" class="text-primary small fw-bold text-decoration-none">Tandai Semua Dibaca</a>
                </div>
            </div>
        </li>

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