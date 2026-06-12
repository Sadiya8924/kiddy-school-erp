@extends('layouts.superadmin')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4">Kelola Fitur: {{ $school->nama_sekolah }}</h4>
        
        <form action="{{ route('superadmin.schools.modules.update', $school->id) }}" method="POST">
            @csrf
            <div class="row">
                @foreach($all_modules as $module)
                <div class="col-md-4 mb-3">
                    <div class="form-check p-3 border rounded shadow-sm">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="modules[]" value="{{ $module->id }}" 
                               {{ in_array($module->id, $active_modules) ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium">
                            <iconify-icon icon="{{ $module->icon }}" class="me-2"></iconify-icon>
                            {{ $module->name }}
                        </label>
                        <div class="small text-muted ms-4">Kategori: {{ $module->category }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">Simpan Perubahan</button>
                <a href="{{ route('superadmin.schools.index') }}" class="btn btn-light rounded-pill px-4 ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection