@extends('layouts.superadmin')

@section('content')
<div class="d-flex flex-column gap-3.5 mt-0 pb-4">

    <div class="card border shadow-sm" style="border-top: 3.5px solid var(--primary-blue) !important; border-radius: 16px !important;">
        <div class="card-body p-4 p-md-4.5">
            
            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <a href="{{ route('superadmin.schools.show', $school->id) }}" class="btn btn-sm btn-light text-muted rounded-circle p-2 shadow-xs d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;" title="Kembali">
                    <iconify-icon icon="solar:alt-arrow-left-bold-duotone" class="fs-5"></iconify-icon>
                </a>
                <div>
                    <h6 class="fw-bold text-dark mb-0.5" style="font-size: 16px;">Kelola Fitur &amp; Modul Sekolah</h6>
                    <span class="text-muted font-medium" style="font-size: 12px;">{{ $school->nama_sekolah }}</span>
                </div>
            </div>

            <form action="{{ route('superadmin.schools.modules.update', $school->id) }}" method="POST">
                @csrf
                <div class="row g-3">
                    @foreach($all_modules as $module)
                    <div class="col-md-4">
                        <div class="p-3 border border-slate-100 bg-slate-50 rounded-3 h-100 d-flex flex-column justify-content-between">
                            <div class="form-check ps-0 mb-1 d-flex align-items-center gap-2">
                                <input class="form-check-input ms-0 border-secondary shadow-none cursor-pointer" style="width: 18px; height: 18px;" type="checkbox" name="modules[]" value="{{ $module->id }}" id="mod_{{ $module->id }}"
                                       {{ in_array($module->id, $active_modules) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark cursor-pointer mb-0" for="mod_{{ $module->id }}" style="font-size: 13.5px;">
                                    <iconify-icon icon="{{ $module->icon }}" class="text-primary me-1.5 fs-5 align-middle"></iconify-icon>
                                    {{ $module->name }}
                                </label>
                            </div>
                            <div class="small text-muted font-medium ps-4 ms-1" style="font-size: 11px;">Kategori: {{ $module->category }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary rounded-3 font-semibold px-4 py-2 shadow-xs d-inline-flex align-items-center gap-1.5" style="font-size: 12.5px;">
                        <iconify-icon icon="solar:diskette-bold-duotone" class="fs-5"></iconify-icon> Simpan Perubahan Fitur
                    </button>
                    <a href="{{ route('superadmin.schools.show', $school->id) }}" class="btn btn-outline-secondary rounded-3 font-semibold px-4 py-2" style="font-size: 12.5px;">Batal</a>
                </div>
            </form>

        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- SUBTLE LOW-KEY FOOTER --}}
    {{-- ========================================================== --}}
    <footer class="text-center py-2 mt-2">
        <span class="text-muted" style="font-size: 11px;">© 2026 TK Ceria - Web TK Al-Hidayah v1.0</span>
    </footer>

</div>
@endsection