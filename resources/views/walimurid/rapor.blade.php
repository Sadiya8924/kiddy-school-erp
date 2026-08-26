@extends('layouts.admin')
@section('title', 'Rapor Akademik Anak')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    <div class="col-12">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Laporan Hasil Belajar (Rapor)</h4>
                <p class="text-muted mb-0 small">Pantau perkembangan akademik dan karakter ananda {{ $siswa->nama_lengkap ?? '' }}</p>
            </div>
        </div>
    </div>

    @if(!$semesterAktif)
    <div class="col-12">
        <div class="card border shadow-none">
            <div class="card-body p-5 text-center">
                <iconify-icon icon="solar:calendar-broken-bold-duotone" class="text-warning mb-3" style="font-size: 60px;"></iconify-icon>
                <h5 class="fw-bold text-dark">Tahun Ajaran Belum Diatur</h5>
                <p class="text-muted" style="font-size: 13px;">Pihak sekolah belum mengaktifkan tahun ajaran baru.</p>
            </div>
        </div>
    </div>
    @elseif(!$rapor || $rapor->status == 'draft')
    <div class="col-12">
        <div class="card border shadow-none">
            <div class="card-body p-5 text-center">
                <iconify-icon icon="solar:document-broken-bold-duotone" class="text-muted mb-3" style="font-size: 60px;"></iconify-icon>
                <h5 class="fw-bold text-dark">Rapor Belum Tersedia</h5>
                <p class="text-muted" style="font-size: 13px;">Wali kelas belum menerbitkan nilai rapor ananda untuk semester ini atau rapor masih dalam status <i>Draft</i>.</p>
            </div>
        </div>
    </div>
    @else

    @php
        // Ambil Template yang sesuai dengan rapor, atau template aktif jika tidak ada
        $template = $rapor->template ?? \App\Models\RaporTemplate::where('is_active', true)->with('items.anak_isian')->first();
        
        // Ekstrak nilai array JSON dari database rapor
        $nilai_json = [];
        if($rapor && $rapor->nilai_json) {
            $nilai_json = is_string($rapor->nilai_json) ? json_decode($rapor->nilai_json, true) : $rapor->nilai_json;
        }
    @endphp

    {{-- TAMPILAN VIEW RAPOR DIGITAL DINAMIS --}}
    <div class="col-12">
        <div class="card border shadow-none mb-4">
            
            {{-- KOP SURAT / HEADER RAPOR --}}
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Capaian Perkembangan Anak</h5>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded px-2 py-1" style="font-size: 11px; font-weight: 600;">
                            Semester {{ $semesterAktif->tahun_ajaran }} ({{ ucfirst($semesterAktif->jenis) }})
                        </span>
                    </div>
                    <div class="text-end text-muted" style="font-size: 12px;">
                        <div>Sekolah: <strong class="text-dark">{{ \App\Models\School::find($siswa->school_id)->nama_sekolah ?? '-' }}</strong></div>
                        <div>Kelas: <strong class="text-dark">{{ $siswa->nama_kelas_aktif ?? '-' }}</strong></div>
                    </div>
                </div>
            </div>

            {{-- BODY RAPOR --}}
            <div class="card-body p-4">
                
                {{-- Info Siswa Singkat --}}
                <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded" style="background-color: var(--bg-body);">
                    @if($siswa->foto_anak)
                        <img src="{{ asset('storage/' . $siswa->foto_anak) }}" class="rounded object-fit-cover shadow-sm" style="width: 50px; height: 50px;">
                    @else
                        <div class="rounded d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 50px; height: 50px; background-color: var(--primary-blue-soft);">
                            {{ strtoupper(substr($siswa->nama_lengkap, 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 15px;">{{ $siswa->nama_lengkap }}</h6>
                        <span class="text-muted" style="font-size: 12px;">NISN: {{ $siswa->nisn ?? '-' }}</span>
                    </div>
                </div>

                @if($template)
                    {{-- 1. LOOPING NARASI PERKEMBANGAN DARI TEMPLATE --}}
                    <div class="mb-5">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 border-bottom pb-2" style="font-size: 14px;">
                            <iconify-icon icon="solar:star-fall-bold-duotone" class="text-warning fs-5"></iconify-icon> Laporan Perkembangan Anak
                        </h6>
                        
                        @foreach($template->items->where('is_bab', true)->sortBy('urutan') as $bab)
                            @php 
                                $hasNarasi = $bab->anak_isian->where('tipe', 'textarea')->count() > 0;
                                $itemNarasi = $bab->anak_isian->whereIn('tipe', ['textarea', 'sub_judul'])->sortBy('urutan')->values(); 
                            @endphp
                            
                            @if($hasNarasi)
                                <div class="mb-4 pl-2">
                                    <strong style="font-size: 14px; color: var(--primary-blue-dark); text-transform: uppercase;">{{ $bab->label }}</strong>
                                    
                                    @foreach($itemNarasi as $isian)
                                        @if($isian->tipe == 'sub_judul')
                                            <div class="mb-1 mt-3"><strong style="font-size: 12px; font-style: italic; color: var(--text-dark);">{{ $isian->label }}</strong></div>
                                        @elseif($isian->tipe == 'textarea')
                                            <div class="mt-2 mb-3">
                                                <strong style="font-size: 12px; color: var(--text-dark);">- {{ $isian->label }}</strong>
                                                <div class="p-3 border rounded text-dark mt-1 shadow-sm" style="font-size: 13px; line-height: 1.6; background-color: #fafafa; white-space: pre-wrap;">{{ $nilai_json[$isian->id] ?? '-' }}</div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- 2. LOOPING MUATAN LOKAL DARI TEMPLATE --}}
                    <div class="mb-5">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 border-bottom pb-2" style="font-size: 14px;">
                            <iconify-icon icon="solar:diploma-verified-bold-duotone" class="text-success fs-5"></iconify-icon> Penilaian Muatan Lokal
                        </h6>
                        <div class="table-responsive border rounded shadow-sm">
                            <table class="table table-borderless table-hover table-sm small mb-0" style="font-size: 12px;">
                                <thead class="bg-light border-bottom">
                                    <tr>
                                        <th width="5%" class="text-center align-middle py-3">No</th>
                                        <th width="75%" class="align-middle py-3">Aspek Perkembangan</th>
                                        <th width="20%" class="text-center align-middle py-3">Hasil Penilaian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $global_no = 1; @endphp
                                    @foreach($template->items->where('is_bab', true)->sortBy('urutan') as $bab)
                                        @php $itemMulok = $bab->anak_isian->where('tipe', 'radio'); @endphp
                                        
                                        @if($itemMulok->count() > 0)
                                            <tr>
                                                <td colspan="3" class="fw-bold text-primary bg-primary-subtle py-2 px-4" style="text-transform: uppercase;">{{ $bab->label }}</td>
                                            </tr>
                                            @foreach($itemMulok as $isian)
                                                <tr class="border-bottom border-light-subtle">
                                                    <td class="text-center py-3 text-muted">{{ $global_no++ }}</td>
                                                    <td class="ps-3 py-3 fw-medium text-dark" style="white-space: normal;">{{ $isian->label }}</td>
                                                    <td class="text-center py-3 fw-bold text-primary fs-6">{{ $nilai_json[$isian->id] ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning text-center">Format template rapor belum tersedia.</div>
                @endif

                {{-- 3. REKAP KEHADIRAN & FISIK --}}
                <div>
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 border-bottom pb-2" style="font-size: 14px;">
                        <iconify-icon icon="solar:accessibility-bold-duotone" class="text-danger fs-5"></iconify-icon> Rekapitulasi Kehadiran & Fisik
                    </h6>
                    <div class="row gx-2">
                        <div class="col-md-2 col-4 mb-2">
                            <div class="p-3 border rounded text-center h-100 shadow-sm" style="background-color: #fafafa;">
                                <h5 class="fw-bold text-dark mb-0">{{ $rapor->sakit ?? 0 }}</h5>
                                <span class="text-muted" style="font-size: 11px;">Sakit</span>
                            </div>
                        </div>
                        <div class="col-md-2 col-4 mb-2">
                            <div class="p-3 border rounded text-center h-100 shadow-sm" style="background-color: #fafafa;">
                                <h5 class="fw-bold text-dark mb-0">{{ $rapor->izin ?? 0 }}</h5>
                                <span class="text-muted" style="font-size: 11px;">Izin</span>
                            </div>
                        </div>
                        <div class="col-md-2 col-4 mb-2">
                            <div class="p-3 border rounded text-center h-100 shadow-sm" style="background-color: #fafafa;">
                                <h5 class="fw-bold text-dark mb-0">{{ $rapor->alfa ?? 0 }}</h5>
                                <span class="text-muted" style="font-size: 11px;">Tanpa Ket.</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="p-3 border rounded text-center h-100 shadow-sm bg-white border-primary-subtle">
                                <h5 class="fw-bold text-primary mb-0">{{ $rapor->tinggi_badan ?? '-' }} <span class="fs-6">cm</span></h5>
                                <span class="text-muted" style="font-size: 11px;">Tinggi Badan</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="p-3 border rounded text-center h-100 shadow-sm bg-white border-primary-subtle">
                                <h5 class="fw-bold text-primary mb-0">{{ $rapor->berat_badan ?? '-' }} <span class="fs-6">kg</span></h5>
                                <span class="text-muted" style="font-size: 11px;">Berat Badan</span>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $fotoKegiatanList = [];
                    if ($rapor && !empty($rapor->foto_kegiatan)) {
                        $fotoKegiatanList = is_string($rapor->foto_kegiatan) ? json_decode($rapor->foto_kegiatan, true) : $rapor->foto_kegiatan;
                    }
                @endphp

                @if(!empty($fotoKegiatanList) && is_array($fotoKegiatanList))
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 border-bottom pb-2" style="font-size: 14px;">
                        <iconify-icon icon="solar:gallery-wide-bold-duotone" class="text-primary fs-5"></iconify-icon> Dokumentasi Foto Kegiatan Terbaik
                    </h6>
                    <div class="row row-cols-2 row-cols-md-3 g-3">
                        @foreach($fotoKegiatanList as $fotoPath)
                            @php
                                $filename = basename($fotoPath);
                                $url = str_starts_with($fotoPath, 'jurnal_media/foto/') 
                                    ? route('jurnal.media', ['type' => 'foto', 'filename' => $filename]) 
                                    : asset('storage/' . $fotoPath);
                            @endphp
                            <div class="col">
                                <div class="p-2 border rounded-3 bg-white shadow-sm h-100">
                                    <img src="{{ $url }}" class="img-fluid rounded-2 object-fit-cover w-100" style="max-height: 200px;" alt="Dokumentasi Kegiatan">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
    @endif

</div>
@endsection