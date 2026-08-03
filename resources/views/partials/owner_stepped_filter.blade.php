@if(Auth::check() && (Auth::user()->isOwner() || Auth::user()->role === 'super_admin'))
  @php
    $user = Auth::user();
    $activeYayasanId = session('active_yayasan_id');
    $activeSchoolId = session('active_school_id');
    
    // Super admin selalu terikat pada yayasan_id miliknya
    if (!$user->isOwner() && $user->yayasan_id) {
        $activeYayasanId = $user->yayasan_id;
    }

    $currentYayasan = $activeYayasanId ? \App\Models\Yayasan::find($activeYayasanId) : null;
    $currentSchool = $activeSchoolId ? \App\Models\School::with('yayasan')->find($activeSchoolId) : null;
  @endphp

  {{-- LEVEL 3: TK AKTIF TERPILIH (Menampilkan Top Control Bar untuk Reset Filter) --}}
  @if($activeSchoolId && $currentSchool)
    <div class="col-12 mb-4">
      <div class="bg-white p-3 rounded-3 border shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          @if($user->isOwner() && $currentSchool->yayasan)
          <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-medium d-flex align-items-center gap-1" style="font-size: 13px;">
            <i class="ti ti-building text-purple"></i> Yayasan: <strong class="text-purple">{{ $currentSchool->yayasan->nama_yayasan }}</strong>
          </span>
          <i class="ti ti-chevron-right text-muted"></i>
          @endif
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold d-flex align-items-center gap-1" style="font-size: 13px;">
            <i class="ti ti-building-school"></i> Sekolah (TK): <strong>{{ $currentSchool->nama_sekolah }}</strong>
          </span>
        </div>

        <div class="d-flex gap-2">
          {{-- Tombol Reset Ke Level Yayasan (Pilih / Ganti TK Lain) --}}
          <form method="POST" action="{{ route('superadmin.switch_school') }}" class="m-0">
            @csrf
            <input type="hidden" name="active_school_id" value="0">
            <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3 d-flex align-items-center gap-1 shadow-none fw-semibold">
              <i class="ti ti-arrow-left"></i>
              <span>Pilih / Ganti TK Lain</span>
            </button>
          </form>

          @if($user->isOwner())
          {{-- Tombol Reset Semua Filter (Kembali ke Level Semua Yayasan - Khusus Owner) --}}
          <form method="POST" action="{{ route('owner.switch_yayasan') }}" class="m-0">
            @csrf
            <input type="hidden" name="active_yayasan_id" value="0">
            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 d-flex align-items-center gap-1 shadow-none fw-semibold">
              <i class="ti ti-rotate"></i>
              <span>Reset Semua Filter</span>
            </button>
          </form>
          @endif
        </div>
      </div>
    </div>

  {{-- LEVEL 1 & 2: BELUM PILIH TK (Menampilkan Card Selector Stepped) --}}
  @else
    <div class="col-12 mb-4">
      
      {{-- STEP 1: KHUSUS OWNER (JIKA BELUM PILIH YAYASAN) --}}
      @if($user->isOwner() && !$activeYayasanId)
        <div class="bg-white p-4 rounded-3 border shadow-sm mb-4">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-purple-subtle text-purple d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #f3e8ff; color: #7e22ce;">
              <i class="ti ti-building-community fs-3"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-1">Langkah 1: Pilih Yayasan</h5>
              <p class="text-muted small mb-0">Silakan pilih salah satu Yayasan terlebih dahulu untuk melihat data fitur pada halaman ini.</p>
            </div>
          </div>
        </div>

        <div class="row gy-4">
          @foreach(\App\Models\Yayasan::where('status', 'aktif')->withCount('schools')->get() as $yys)
            @php
              $schoolIds = $yys->schools->pluck('id');
              $totalSiswa = \Illuminate\Support\Facades\DB::table('siswas')->whereIn('school_id', $schoolIds)->count();
              $totalKelas = \Illuminate\Support\Facades\DB::table('kelas')->whereIn('school_id', $schoolIds)->count();
            @endphp
            <div class="col-md-6 col-lg-4">
              <div class="card border shadow-none h-100 card-clickable">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                  <div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div class="d-flex align-items-center gap-3">
                        @if($yys->logo_yayasan)
                          <img src="{{ asset('storage/' . $yys->logo_yayasan) }}" class="rounded-circle border" width="45" height="45" style="object-fit: cover;">
                        @else
                          <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="ti ti-building fs-4"></i>
                          </div>
                        @endif
                        <div>
                          <h6 class="fw-bold text-dark mb-0 fs-5">{{ $yys->nama_yayasan }}</h6>
                          <small class="text-muted">{{ $yys->kode_yayasan ?? '-' }}</small>
                        </div>
                      </div>
                      <span class="badge bg-purple-subtle text-purple rounded-pill px-2.5 py-1" style="background-color: #f3e8ff; color: #7e22ce;">{{ $yys->schools_count }} TK</span>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3" style="font-size: 13px;">
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted"><i class="ti ti-building-school text-primary me-1"></i> Jumlah TK:</span>
                        <span class="fw-bold text-dark">{{ $yys->schools_count }} Sekolah</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted"><i class="ti ti-chalkboard text-purple me-1"></i> Jumlah Kelas:</span>
                        <span class="fw-bold text-dark">{{ $totalKelas }} Kelas</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="ti ti-users text-success me-1"></i> Jumlah Siswa:</span>
                        <span class="fw-bold text-dark">{{ $totalSiswa }} Siswa</span>
                      </div>
                    </div>
                  </div>

                  <form method="POST" action="{{ route('owner.switch_yayasan') }}">
                    @csrf
                    <input type="hidden" name="active_yayasan_id" value="{{ $yys->id }}">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-medium d-flex align-items-center justify-content-center gap-2">
                      <span>Pilih {{ $yys->nama_yayasan }}</span>
                      <i class="ti ti-arrow-right"></i>
                    </button>
                  </form>

                </div>
              </div>
            </div>
          @endforeach
        </div>

      {{-- STEP 2: SUDAH PILIH YAYASAN (SERTA KHUSUS SUPER ADMIN), SEKARANG PILIH TK --}}
      @elseif($currentYayasan)
        <div class="bg-white p-4 rounded-3 border shadow-sm mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
              <i class="ti ti-building-school fs-3"></i>
            </div>
            <div>
              <div class="d-flex align-items-center gap-2">
                <h5 class="fw-bold text-dark mb-0">{{ $user->isOwner() ? 'Langkah 2: ' : '' }}Pilih Sekolah (TK)</h5>
                <span class="badge bg-purple-subtle text-purple rounded-pill px-3 py-1 fw-bold" style="background-color: #f3e8ff; color: #7e22ce;">Yayasan: {{ $currentYayasan->nama_yayasan }}</span>
              </div>
              <p class="text-muted small mb-0 mt-1">Pilih salah satu TK di bawah {{ $currentYayasan->nama_yayasan }} untuk kelola data pada halaman ini.</p>
            </div>
          </div>

          @if($user->isOwner())
          <form method="POST" action="{{ route('owner.switch_yayasan') }}">
            @csrf
            <input type="hidden" name="active_yayasan_id" value="0">
            <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill px-3 d-flex align-items-center gap-1 shadow-none fw-semibold">
              <i class="ti ti-arrow-left"></i>
              <span>Kembali ke List Yayasan</span>
            </button>
          </form>
          @endif
        </div>

        <div class="row gy-4">
          @php
            $schoolsInYayasan = \App\Models\School::where('yayasan_id', $currentYayasan->id)->where('status', 'aktif')->withCount(['siswas'])->get();
          @endphp

          @forelse($schoolsInYayasan as $sch)
            @php
              $totalKelasSch = \Illuminate\Support\Facades\DB::table('kelas')->where('school_id', $sch->id)->count();
            @endphp
            <div class="col-md-6 col-lg-4">
              <div class="card border shadow-none h-100 card-clickable">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                  <div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div class="d-flex align-items-center gap-3">
                        @if($sch->logo_sekolah)
                          <img src="{{ asset('storage/' . $sch->logo_sekolah) }}" class="rounded" width="45" height="45" style="object-fit: cover;">
                        @else
                          <div class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="ti ti-building-school fs-4"></i>
                          </div>
                        @endif
                        <div>
                          <h6 class="fw-bold text-dark mb-0 fs-5">{{ $sch->nama_sekolah }}</h6>
                          <small class="text-muted">NPSN: {{ $sch->npsn ?? '-' }}</small>
                        </div>
                      </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3" style="font-size: 13px;">
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted"><i class="ti ti-user-check text-primary me-1"></i> Kepala Sekolah:</span>
                        <span class="fw-bold text-dark">{{ $sch->kepala_sekolah ?? '-' }}</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted"><i class="ti ti-chalkboard text-purple me-1"></i> Jumlah Kelas:</span>
                        <span class="fw-bold text-dark">{{ $totalKelasSch }} Kelas</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="ti ti-users text-success me-1"></i> Jumlah Siswa:</span>
                        <span class="fw-bold text-dark">{{ $sch->siswas_count }} Siswa</span>
                      </div>
                    </div>
                  </div>

                  <form method="POST" action="{{ route('superadmin.switch_school') }}">
                    @csrf
                    <input type="hidden" name="active_school_id" value="{{ $sch->id }}">
                    <button type="submit" class="btn btn-success w-100 rounded-pill fw-medium d-flex align-items-center justify-content-center gap-2">
                      <span>Pilih {{ $sch->nama_sekolah }}</span>
                      <i class="ti ti-check"></i>
                    </button>
                  </form>

                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="card border shadow-none text-center py-5">
                <div class="card-body">
                  <i class="ti ti-building-school fs-1 text-muted mb-2 d-block"></i>
                  <h6 class="fw-semibold text-dark">Belum Ada Sekolah Terdaftar</h6>
                  <p class="text-muted small">Belum ada sekolah (TK) yang terdaftar di {{ $currentYayasan->nama_yayasan }}.</p>
                </div>
              </div>
            </div>
          @endforelse
        </div>

      @endif

    </div>
  @endif
@endif
