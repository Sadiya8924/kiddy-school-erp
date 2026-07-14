@extends('layouts.admin')

@section('konten_halaman')
<div class="row gx-3 gy-3 mt-0">
    <div class="col-12">
        <div class="card border shadow-none mb-0" style="background-color: var(--primary-blue-soft); border-color: var(--primary-blue-border) !important;">
            <div class="card-body px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <a href="/admin/template" class="btn btn-light btn-sm rounded-pill px-3 mb-2 shadow-none"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
                    <h4 class="fw-bold text-dark mb-1">Atur Layout Rapor: {{ $template->nama_template }}</h4>
                    <p class="text-muted mb-0 small">Kelola struktur isi rapor, narasi, dan komponen mulok.</p>
                </div>
                <div class="d-flex align-items-center gap-2 text-muted small fw-medium">
                    <iconify-icon icon="solar:settings-bold-duotone" class="fs-5 text-primary"></iconify-icon>
                    <span>Struktur template aktif</span>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row gx-3 gy-3 mt-0">
    <!-- KOLOM KIRI: FORM PENGATURAN -->
    <div class="col-md-4">
        
        <!-- PENGATURAN MULOK GLOBAL -->
        <div class="card border shadow-none mb-3 border-start border-4 border-warning">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 border-bottom pb-2 text-dark d-flex align-items-center gap-2"><iconify-icon icon="solar:settings-minimalistic-bold-duotone" class="text-primary fs-5"></iconify-icon> Pengaturan Pilihan Mulok</h6>
                <form action="/admin/template/{{ $template->id }}/opsi-mulok" method="POST">
                    @csrf
                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">Ketik pilihan (pisahkan koma):</small>
                        <input type="text" name="opsi_mulok" class="form-control shadow-none" 
                               value="{{ is_array($template->opsi_mulok) ? implode(', ', $template->opsi_mulok) : '' }}" 
                               placeholder="Contoh: BB, MB, BSH, SB">
                    </div>
                    <button type="submit" class="btn btn-warning rounded-pill w-100 fw-bold shadow-none">Simpan Opsi Mulok</button>
                </form>
            </div>
        </div>

        <!-- FORM TAMBAH ITEM -->
        <div class="card border shadow-none">
            <div class="card-header bg-light border-bottom fw-bold text-dark">Tambah Struktur Rapor</div>
            <div class="card-body p-4">
                <form action="/admin/template/{{ $template->id }}/items" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Inputan</label>
                        <select name="jenis_input" class="form-select" id="jenisInput" onchange="toggleForm()" required>
                            <option value="bab" {{ old('jenis_input') == 'bab' ? 'selected' : '' }}>📚 Buat BAB Utama (Accordion)</option>
                            <option value="sub_judul" {{ old('jenis_input') == 'sub_judul' ? 'selected' : '' }}>📌 Buat Sub-Judul (Header 2)</option>
                            <option value="isian_narasi" {{ old('jenis_input') == 'isian_narasi' ? 'selected' : '' }}>📝 Buat Isian Narasi</option>
                            <option value="isian_mulok" {{ old('jenis_input') == 'isian_mulok' ? 'selected' : '' }}>🔘 Buat Isian Mulok (Tabel)</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="parentBabGroup">
                        <label class="form-label fw-semibold text-danger">Masukkan ke dalam BAB:</label>
                        <select name="parent_id" class="form-select border-danger">
                            @foreach($template->items->where('is_bab', true) as $bab)
                                <option value="{{ $bab->id }}" {{ old('parent_id') == $bab->id ? 'selected' : '' }}>{{ $bab->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" id="labelJudul">Nama BAB Utama</label>
                        <input type="text" name="label" class="form-control" placeholder="Ketik di sini..." required autocomplete="off">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan ke Struktur</button>
                </form>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: PREVIEW LAYOUT -->
    <div class="col-md-8">
            <div class="card border shadow-none">
            <div class="card-header bg-light fw-bold text-dark">Preview Struktur Rapor</div>
            <div class="card-body p-4">
                
                @if($template->items->where('is_bab', true)->count() == 0)
                    <div class="text-center py-4 text-muted">Belum ada struktur.</div>
                @else
                    <div class="accordion" id="raporAccordion">
                        @foreach($template->items->where('is_bab', true)->sortBy('urutan') as $bab)
                        <div class="accordion-item mb-3 border shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button bg-light text-primary fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#bab-{{ $bab->id }}">
                                    {{ $bab->label }}
                                </button>
                            </h2>
                            <div id="bab-{{ $bab->id }}" class="accordion-collapse collapse show">
                                <div class="accordion-body p-4">
                                    
                                    @php
                                        // Ambil semua anak dan urutkan sesuai waktu input (urutan)
                                        $items = $bab->anak_isian->sortBy('urutan')->values();
                                        $inTable = false;
                                    @endphp

                                    @forelse($items as $index => $isian)

                                        {{-- JIKA TIPE ADALAH MULOK (RADIO) --}}
                                        @if($isian->tipe == 'radio')
                                            
                                            {{-- Jika belum ada tabel yang terbuka, buka tabel baru --}}
                                            @if(!$inTable)
                                                <div class="table-responsive mt-3 mb-3">
                                                    <table class="table table-bordered table-sm align-middle">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Mata Pelajaran (Mulok)</th>
                                                                @if($template->opsi_mulok)
                                                                    @foreach($template->opsi_mulok as $opsi)
                                                                        <th class="text-center" style="width: 10%;">{{ $opsi }}</th>
                                                                    @endforeach
                                                                @endif
                                                                <th class="text-center" style="width: 10%;">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                @php $inTable = true; @endphp
                                            @endif

                                            {{-- Baris Isi Mulok --}}
                                            <tr>
                                                <td class="fw-medium">{{ $isian->label }}</td>
                                                @if($template->opsi_mulok)
                                                    @foreach($template->opsi_mulok as $opsi)
                                                        <td class="text-center"><input type="radio" disabled></td>
                                                    @endforeach
                                                @else
                                                    <td colspan="4" class="text-danger small">Opsi Mulok belum diatur!</td>
                                                @endif
                                                <td class="text-center">
                                                    <form action="/admin/template/items/{{ $isian->id }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent"><i class="ti ti-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>

                                            {{-- Tutup tabel jika item berikutnya BUKAN Mulok, atau jika ini item terakhir --}}
                                            @if($index == count($items) - 1 || $items[$index+1]->tipe != 'radio')
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @php $inTable = false; @endphp
                                            @endif

                                        {{-- JIKA BUKAN MULOK (SUB-JUDUL ATAU NARASI) --}}
                                        @else
                                            
                                            @if($isian->tipe == 'sub_judul')
                                                <div class="mt-4 mb-3 pb-1 border-bottom d-flex justify-content-between align-items-center">
                                                    <h6 class="fw-bold text-primary m-0">{{ $isian->label }}</h6>
                                                    <form action="/admin/template/items/{{ $isian->id }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent"><i class="ti ti-trash"></i></button>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                                    <div class="w-100">
                                                        <label class="fw-semibold text-dark mb-1">{{ $isian->label }}</label>
                                                        <textarea class="form-control bg-light" rows="2" disabled placeholder="Guru akan mengetik narasi di sini..."></textarea>
                                                    </div>
                                                    <form action="/admin/template/items/{{ $isian->id }}" method="POST" class="ms-3 mt-3">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></button>
                                                    </form>
                                                </div>
                                            @endif

                                        @endif
                                    @empty
                                        <div class="text-muted small text-center my-2">Belum ada isian di dalam BAB ini.</div>
                                    @endforelse

                                </div>
                            </div>
                        </div>
                        
                        <!-- Tombol Hapus BAB -->
                        <div class="text-end mb-4" style="margin-top: -10px;">
                            <form action="/admin/template/items/{{ $bab->id }}" method="POST" onsubmit="return confirm('Hapus BAB ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm text-danger text-decoration-underline border-0 bg-transparent">Hapus BAB "{{ $bab->label }}"</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<script>
function toggleForm() {
    let jenis = document.getElementById('jenisInput').value;
    let parentGroup = document.getElementById('parentBabGroup');
    let labelJudul = document.getElementById('labelJudul');

    if(jenis === 'bab') {
        parentGroup.classList.add('d-none');
        labelJudul.innerText = 'Nama BAB Utama';
    } else {
        parentGroup.classList.remove('d-none');
        
        if(jenis === 'sub_judul') {
            labelJudul.innerText = 'Teks Sub-Judul (Misal: 1. Refleksi Orang Tua)';
        } else if(jenis === 'isian_narasi') {
            labelJudul.innerText = 'Judul Isian Narasi';
        } else if(jenis === 'isian_mulok') {
            labelJudul.innerText = 'Nama Pelajaran (Misal: Melukis)';
        }
    }
}

// Auto-trigger saat halaman refresh biar form nggak balik nutup
document.addEventListener("DOMContentLoaded", function() {
    toggleForm();
});
</script>
@endsection