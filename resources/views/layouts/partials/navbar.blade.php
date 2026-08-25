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
        <!-- TM-37: WALI MURID CHILD SWITCHER -->
        <!-- ======================================================== -->
        @if(Auth::check() && Auth::user()->isWaliMurid())
        @php
            $anakList = \App\Models\Siswa::where('email', Auth::user()->email)
                ->orWhere('user_id', Auth::user()->id)->get();
            $activeChildId = session('active_child_id') ?: optional($anakList->first())->id;
        @endphp
        @if($anakList->count() > 0)
        <li class="nav-item d-none d-md-block me-3">
            <form action="{{ route('switch-child') }}" method="POST" id="formSwitchChild" class="d-flex align-items-center gap-2">
                @csrf
                <iconify-icon icon="solar:user-speak-bold-duotone" class="fs-6 text-primary"></iconify-icon>
                <select name="child_id" class="form-select border-0 fw-bold shadow-none" onchange="document.getElementById('formSwitchChild').submit()" style="min-width: 210px; background-color: var(--primary-blue-soft); color: var(--primary-blue-dark); border-radius: 8px;">
                    @foreach($anakList as $anak)
                        <option value="{{ $anak->id }}" {{ $activeChildId == $anak->id ? 'selected' : '' }}>
                            Anak: {{ $anak->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </form>
        </li>
        @endif
        @endif

        <!-- ======================================================== -->
        <!-- TM-25: SEMESTER SWITCHER FOR STAFF -->
        <!-- ======================================================== -->
        @if(Auth::check() && !Auth::user()->isWaliMurid())
        @php
            $semesters = \App\Models\Semester::all();
            $activeSemId = session('active_semester_id') ?: optional(\App\Models\Semester::where('is_active', true)->first())->id;
        @endphp
        <li class="nav-item d-none d-md-block me-3">
            <form action="{{ route('pengaturan.semester.switch') }}" method="POST" id="formSwitchSemester" class="d-flex align-items-center gap-2">
                @csrf
                <iconify-icon icon="solar:calendar-mark-bold-duotone" class="fs-6 text-success"></iconify-icon>
                <select name="semester_id" class="form-select border-0 fw-bold shadow-none" onchange="document.getElementById('formSwitchSemester').submit()" style="min-width: 170px; background-color: #f0fdf4; color: #166534; border-radius: 8px;">
                    @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}" {{ $activeSemId == $sem->id ? 'selected' : '' }}>
                            {{ $sem->tahun_ajaran }} ({{ ucfirst($sem->jenis) }})
                        </option>
                    @endforeach
                </select>
            </form>
        </li>
        @endif
        
        <!-- ======================================================== -->
        <!-- YAYASAN SWITCHER (KHUSUS OWNER) -->
        <!-- ======================================================== -->
        @if(Auth::check() && Auth::user()->isOwner())
        <li class="nav-item d-none d-md-block me-3">
            <form action="{{ route('owner.switch_yayasan') }}" method="POST" id="formSwitchYayasan" class="d-flex align-items-center gap-2">
                @csrf
                <iconify-icon icon="solar:city-bold-duotone" class="fs-6 text-purple"></iconify-icon>
                <select name="active_yayasan_id" class="form-select border-0 fw-bold shadow-none" onchange="document.getElementById('formSwitchYayasan').submit()" style="min-width: 190px; background-color: #f3e8ff; color: #7e22ce; border-radius: 8px;">
                    <option value="">-- Semua Yayasan --</option>
                    @foreach(\App\Models\Yayasan::where('status', 'aktif')->get() as $yayasan)
                        <option value="{{ $yayasan->id }}" {{ session('active_yayasan_id') == $yayasan->id ? 'selected' : '' }}>
                            Yayasan: {{ $yayasan->nama_yayasan }}
                        </option>
                    @endforeach
                </select>
            </form>
        </li>
        @endif

        <!-- ======================================================== -->
        <!-- SCHOOL SWITCHER (SUPER ADMIN) -->
        <!-- ======================================================== -->
        @if(Auth::check() && Auth::user()->isSuperAdmin())
        <li class="nav-item d-none d-md-block me-4 border-end pe-4">
            <form action="{{ route('superadmin.switch_school') }}" method="POST" id="formSwitchSchool" class="d-flex align-items-center gap-2">
                @csrf
                <iconify-icon icon="solar:buildings-bold-duotone" class="fs-6 text-primary"></iconify-icon>
                <select name="active_school_id" class="form-select border-0 fw-bold shadow-none" onchange="document.getElementById('formSwitchSchool').submit()" style="min-width: 200px; background-color: var(--primary-blue-soft); color: var(--primary-blue-dark); border-radius: 8px;">
                    <option value="">-- Mode Pusat (Semua Data) --</option>
                    @php
                        $querySchools = \App\Models\School::where('status', 'aktif');
                        if (session('active_yayasan_id')) {
                            $querySchools->where('yayasan_id', session('active_yayasan_id'));
                        } elseif (!Auth::user()->isOwner() && Auth::user()->yayasan_id) {
                            $querySchools->where('yayasan_id', Auth::user()->yayasan_id);
                        }
                    @endphp
                    @foreach($querySchools->get() as $school)
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
          
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up border-0 shadow-sm rounded-4 mt-2" aria-labelledby="drop2" style="min-width: 240px;">
            <div class="message-body">
              
              <div class="d-flex align-items-center px-4 py-3 border-bottom" style="border-color: #f3f4f6;">
                <img src="{{ asset('template2/images/profile/user-1.jpg') }}" alt="Profile" width="45" height="45" class="rounded-circle object-fit-cover me-3 shadow-sm">
                <div>
                  <h6 class="mb-0 fs-5 fw-semibold text-dark">{{ Auth::user()->name ?? 'Administrator' }}</h6>
                  <p class="mb-0 text-muted fw-medium text-capitalize small">{{ str_replace('_', ' ', Auth::user()->role ?? 'admin') }}</p>
                </div>
              </div>
              
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