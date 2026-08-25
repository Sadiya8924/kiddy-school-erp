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
    <div class="col-12 mb-3.5">
      <div class="bg-white p-3.5 rounded-3 border shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="border-radius: 16px !important;">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          @if($user->isOwner() && $currentSchool->yayasan)
          <span class="badge bg-purple-subtle text-purple px-3 py-2 rounded-pill font-semibold d-flex align-items-center gap-1.5" style="font-size: 12px; background-color: #f3e8ff; color: #7e22ce;">
            <iconify-icon icon="solar:buildings-bold-duotone" class="fs-5"></iconify-icon> Yayasan: <strong>{{ $currentSchool->yayasan->nama_yayasan }}</strong>
          </span>
          <iconify-icon icon="solar:alt-arrow-right-line-duotone" class="text-muted fs-5"></iconify-icon>
          @endif
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill font-bold d-flex align-items-center gap-1.5" style="font-size: 12px;">
            <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-5"></iconify-icon> Sekolah (TK): <strong>{{ $currentSchool->nama_sekolah }}</strong>
          </span>
        </div>

        <div class="d-flex gap-2 flex-wrap">
          {{-- Tombol Reset Ke Level Yayasan (Pilih / Ganti TK Lain) --}}
          <form method="POST" action="{{ route('superadmin.switch_school') }}" class="m-0">
            @csrf
            <input type="hidden" name="active_school_id" value="0">
            <button type="submit" class="btn btn-outline-primary btn-sm rounded-3 px-3 py-1.5 d-flex align-items-center gap-1 shadow-xs font-semibold" style="font-size: 12px;">
              <iconify-icon icon="solar:arrow-left-line-duotone" class="fs-5"></iconify-icon>
              <span>Pilih / Ganti TK Lain</span>
            </button>
          </form>

          @if($user->isOwner())
          {{-- Tombol Reset Semua Filter (Kembali ke Level Semua Yayasan - Khusus Owner) --}}
          <form method="POST" action="{{ route('owner.switch_yayasan') }}" class="m-0">
            @csrf
            <input type="hidden" name="active_yayasan_id" value="0">
            <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 px-3 py-1.5 d-flex align-items-center gap-1 shadow-xs font-semibold" style="font-size: 12px;">
              <iconify-icon icon="solar:refresh-circle-bold-duotone" class="fs-5"></iconify-icon>
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
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid #8b5cf6 !important; border-radius: 16px !important;">
          <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle text-purple d-flex align-items-center justify-content-center shadow-xs flex-shrink-0" style="width: 50px; height: 50px; background-color: #f3e8ff; color: #7e22ce;">
                <iconify-icon icon="solar:buildings-bold-duotone" class="fs-2"></iconify-icon>
              </div>
              <div>
                <h5 class="fw-bold text-dark mb-0.5" style="font-size: 16px;">Langkah 1: Pilih Yayasan</h5>
                <p class="text-muted small mb-0" style="font-size: 12px;">Silakan pilih salah satu Yayasan terlebih dahulu untuk melihat data fitur pada halaman ini.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-4">
          @foreach(\App\Models\Yayasan::where('status', 'aktif')->withCount('schools')->get() as $yys)
            @php
              $schoolIds = $yys->schools->pluck('id');
              $totalSiswa = \Illuminate\Support\Facades\DB::table('siswas')->whereIn('school_id', $schoolIds)->count();
              $totalKelas = \Illuminate\Support\Facades\DB::table('kelas')->whereIn('school_id', $schoolIds)->count();
            @endphp
            <div class="col-md-6 col-lg-4">
              <div class="card border shadow-sm h-100 card-clickable" style="border-radius: 16px !important; border-top: 3px solid #8b5cf6 !important;">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                  <div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div class="d-flex align-items-center gap-3">
                        @if($yys->logo_yayasan)
                          <img src="{{ asset('storage/' . $yys->logo_yayasan) }}" class="rounded object-fit-cover shadow-xs flex-shrink-0" width="44" height="44">
                        @else
                          <div class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold shadow-xs flex-shrink-0" style="width: 44px; height: 44px; font-size: 14px;">
                            {{ strtoupper(substr($yys->nama_yayasan, 0, 2)) }}
                          </div>
                        @endif
                        <div>
                          <h6 class="fw-bold text-dark mb-0" style="font-size: 15px;">{{ $yys->nama_yayasan }}</h6>
                          <span class="badge bg-slate-100 text-dark border font-semibold mt-1" style="font-size: 10.5px;">{{ $yys->kode_yayasan ?? '-' }}</span>
                        </div>
                      </div>
                      <span class="badge rounded-pill px-2.5 py-1 font-semibold" style="background-color: #f3e8ff; color: #7e22ce; font-size: 11px;">{{ $yys->schools_count }} TK</span>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-3 mb-3 border border-slate-100" style="font-size: 12.5px;">
                      <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-muted"><iconify-icon icon="solar:buildings-2-bold-duotone" class="text-primary me-1 align-middle fs-5"></iconify-icon> Jumlah TK:</span>
                        <span class="fw-bold text-dark">{{ $yys->schools_count }} Sekolah</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-muted"><iconify-icon icon="solar:black-hole-bold-duotone" class="text-purple me-1 align-middle fs-5"></iconify-icon> Jumlah Kelas:</span>
                        <span class="fw-bold text-dark">{{ $totalKelas }} Kelas</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted"><iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-success me-1 align-middle fs-5"></iconify-icon> Jumlah Siswa:</span>
                        <span class="fw-bold text-dark">{{ $totalSiswa }} Siswa</span>
                      </div>
                    </div>
                  </div>

                  <form method="POST" action="{{ route('owner.switch_yayasan') }}">
                    @csrf
                    <input type="hidden" name="active_yayasan_id" value="{{ $yys->id }}">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 font-semibold py-2 d-flex align-items-center justify-content-center gap-1.5" style="font-size: 12.5px;">
                      <span>Pilih {{ $yys->nama_yayasan }}</span>
                      <iconify-icon icon="solar:alt-arrow-right-line-duotone" class="fs-5"></iconify-icon>
                    </button>
                  </form>

                </div>
              </div>
            </div>
          @endforeach
        </div>

      {{-- STEP 2: SUDAH PILIH YAYASAN (SERTA KHUSUS SUPER ADMIN), SEKARANG PILIH TK --}}
      @elseif($currentYayasan)
        <div class="card border shadow-sm mb-4" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
          <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-2"></iconify-icon>
              </div>
              <div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                  <h5 class="fw-bold text-dark mb-0" style="font-size: 16px;">{{ $user->isOwner() ? 'Langkah 2: ' : '' }}Pilih Sekolah (TK)</h5>
                  <span class="badge rounded-pill px-3 py-1 font-semibold" style="background-color: #f3e8ff; color: #7e22ce; font-size: 11px;">Yayasan: {{ $currentYayasan->nama_yayasan }}</span>
                </div>
                <p class="text-muted small mb-0 mt-1" style="font-size: 12px;">Pilih salah satu TK di bawah {{ $currentYayasan->nama_yayasan }} untuk kelola data pada halaman ini.</p>
              </div>
            </div>

            @if($user->isOwner())
            <form method="POST" action="{{ route('owner.switch_yayasan') }}" class="m-0">
              @csrf
              <input type="hidden" name="active_yayasan_id" value="0">
              <button type="submit" class="btn btn-outline-secondary btn-sm rounded-3 font-semibold px-3 py-1.5 d-flex align-items-center gap-1 shadow-xs" style="font-size: 12px;">
                <iconify-icon icon="solar:arrow-left-line-duotone" class="fs-5"></iconify-icon>
                <span>Kembali ke List Yayasan</span>
              </button>
            </form>
            @endif
          </div>
        </div>

        <div class="row g-4">
          @php
            $schoolsInYayasan = \App\Models\School::where('yayasan_id', $currentYayasan->id)->where('status', 'aktif')->withCount(['siswas'])->get();
          @endphp

          @forelse($schoolsInYayasan as $sch)
            @php
              $totalKelasSch = \Illuminate\Support\Facades\DB::table('kelas')->where('school_id', $sch->id)->count();
            @endphp
            <div class="col-md-6 col-lg-4">
              <div class="card border shadow-sm h-100 card-clickable" style="border-radius: 16px !important; border-top: 3px solid var(--primary-blue) !important;">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                  <div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div class="d-flex align-items-center gap-3">
                        @if($sch->logo_sekolah)
                          <img src="{{ asset('storage/' . $sch->logo_sekolah) }}" class="rounded object-fit-cover shadow-xs flex-shrink-0" width="44" height="44">
                        @else
                          <div class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold shadow-xs flex-shrink-0" style="width: 44px; height: 44px; font-size: 14px;">
                            {{ strtoupper(substr($sch->nama_sekolah, 0, 2)) }}
                          </div>
                        @endif
                        <div>
                          <h6 class="fw-bold text-dark mb-0" style="font-size: 15px;">{{ $sch->nama_sekolah }}</h6>
                          <span class="badge bg-slate-100 text-dark border font-semibold mt-1" style="font-size: 10.5px;">NPSN: {{ $sch->npsn ?? '-' }}</span>
                        </div>
                      </div>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-3 mb-3 border border-slate-100" style="font-size: 12.5px;">
                      <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-muted"><iconify-icon icon="solar:user-check-bold-duotone" class="text-primary me-1 align-middle fs-5"></iconify-icon> Kepala Sekolah:</span>
                        <span class="fw-bold text-dark">{{ $sch->kepala_sekolah ?? '-' }}</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-muted"><iconify-icon icon="solar:black-hole-bold-duotone" class="text-purple me-1 align-middle fs-5"></iconify-icon> Jumlah Kelas:</span>
                        <span class="fw-bold text-dark">{{ $totalKelasSch }} Kelas</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted"><iconify-icon icon="solar:users-group-rounded-bold-duotone" class="text-success me-1 align-middle fs-5"></iconify-icon> Jumlah Siswa:</span>
                        <span class="fw-bold text-dark">{{ $sch->siswas_count }} Siswa</span>
                      </div>
                    </div>
                  </div>

                  <form method="POST" action="{{ route('superadmin.switch_school') }}">
                    @csrf
                    <input type="hidden" name="active_school_id" value="{{ $sch->id }}">
                    <button type="submit" class="btn btn-success w-100 rounded-3 font-semibold py-2 d-flex align-items-center justify-content-center gap-1.5" style="font-size: 12.5px;">
                      <span>Pilih {{ $sch->nama_sekolah }}</span>
                      <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-5"></iconify-icon>
                    </button>
                  </form>

                </div>
              </div>
            </div>
          @empty
            <div class="col-12 text-center py-5">
              <div class="py-3">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                  <iconify-icon icon="solar:buildings-2-bold-duotone" class="fs-2"></iconify-icon>
                </div>
                <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Ada Sekolah Terdaftar</h6>
                <p class="text-muted small mb-0">Belum ada sekolah (TK) yang terdaftar di {{ $currentYayasan->nama_yayasan }}.</p>
              </div>
            </div>
          @endforelse
        </div>

      @endif

    </div>
  @endif
@endif
