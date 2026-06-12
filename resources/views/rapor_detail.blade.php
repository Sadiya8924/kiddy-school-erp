@extends('layouts.admin')

@section('konten_halaman')
<div class="d-print-none mb-4 d-flex justify-content-between">
    <a href="/cetak-rapor" class="btn btn-light rounded-pill px-4">
        <i class="ti ti-arrow-left me-1"></i> Kembali
    </a>
    <button onclick="window.print()" class="btn btn-primary rounded-pill px-4" {{ !$rapor ? 'disabled' : '' }}>
        <i class="ti ti-printer me-1"></i> Cetak ke PDF / Printer
    </button>
</div>

@php
    // Ambil Template yang sesuai dengan rapor, atau template aktif jika tidak ada
    $template = $rapor && $rapor->template ? $rapor->template : \App\Models\RaporTemplate::where('is_active', true)->with('items.anak_isian')->first();
    // Ambil nilai array JSON dari database
    $nilai_json = [];
    if($rapor && $rapor->nilai_json) {
        $nilai_json = is_string($rapor->nilai_json) ? json_decode($rapor->nilai_json, true) : $rapor->nilai_json;
    }
@endphp

{{-- AREA RAPOR (MIRIP WORD) --}}
<div class="rapor-paper shadow-sm mx-auto bg-white border" style="max-width: 900px; color: #000; font-family: 'Times New Roman', serif; padding: 40px;">
    
    {{-- JUDUL UTAMA --}}
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1" style="text-decoration: underline; letter-spacing: 1px;">LAPORAN PERKEMBANGAN PESERTA DIDIK</h4>
        <p class="mb-0 small">{{ auth()->user()->school->nama_sekolah ?? 'Taman Kanak-Kanak' }}</p>
    </div>

    {{-- TABEL IDENTITAS --}}
    <div class="row mb-3">
        <div class="col-7">
            <table class="table table-sm table-borderless small mb-0" style="font-size: 13px;">
                <tr><td width="130">Nama Sekolah</td><td width="10">:</td><td class="fw-bold">{{ auth()->user()->school->nama_sekolah ?? '-' }}</td></tr>
                <tr><td>Nama Murid</td><td>:</td><td class="fw-bold">{{ $siswa->nama_lengkap }}</td></tr>
                <tr><td>NIK</td><td>:</td><td>{{ $siswa->nik ?? '-' }}</td></tr>
                <tr><td>Fase</td><td>:</td><td>Fondasi</td></tr>
            </table>
        </div>
        <div class="col-5">
            <table class="table table-sm table-borderless small mb-0" style="font-size: 13px;">
                <tr><td width="110">Kelas</td><td width="10">:</td><td class="fw-bold">{{ $siswa->nama_kelas_aktif }}</td></tr>
                <tr><td>Tahun Ajaran</td><td>:</td><td class="fw-bold">{{ $rapor?->tahun_ajaran ?? '-' }}</td></tr>
                <tr>
                    <td>Semester</td><td>:</td>
                    <td class="fw-bold">
                        @if($rapor?->semester == 'Ganjil') 1 (Ganjil)
                        @elseif($rapor?->semester == 'Genap') 2 (Genap)
                        @else {{ $rapor?->semester ?? '-' }}
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <hr style="border-top: 2px solid #000; margin: 15px 0;">

    {{-- DATA FISIK & KEHADIRAN --}}
    <div class="row mb-4 text-center small">
        <div class="col-2 border py-2" style="border: 1px solid #000;">
            <div class="text-muted" style="font-size: 11px;">Tinggi Badan</div>
            <div class="fw-bold" style="font-size: 12px;">{{ $rapor?->tinggi_badan ?? '-' }} cm</div>
        </div>
        <div class="col-2 border py-2" style="border: 1px solid #000;">
            <div class="text-muted" style="font-size: 11px;">Berat Badan</div>
            <div class="fw-bold" style="font-size: 12px;">{{ $rapor?->berat_badan ?? '-' }} kg</div>
        </div>
        <div class="col-2 border py-2" style="border: 1px solid #000;">
            <div class="text-muted" style="font-size: 11px;">Sakit</div>
            <div class="fw-bold" style="font-size: 12px;">{{ $rapor?->sakit ?? '0' }} Hari</div>
        </div>
        <div class="col-2 border py-2" style="border: 1px solid #000;">
            <div class="text-muted" style="font-size: 11px;">Izin</div>
            <div class="fw-bold" style="font-size: 12px;">{{ $rapor?->izin ?? '0' }} Hari</div>
        </div>
        <div class="col-2 border py-2" style="border: 1px solid #000;">
            <div class="text-muted" style="font-size: 11px;">Alfa</div>
            <div class="fw-bold" style="font-size: 12px;">{{ $rapor?->alfa ?? '0' }} Hari</div>
        </div>
    </div>

    @if($rapor && $template)
        {{-- BAGIAN NARASI PERKEMBANGAN (LOOPING DARI TEMPLATE) --}}
        <div class="mb-4">
            <h6 class="fw-bold mb-2" style="font-size: 13px; text-transform: uppercase;">Laporan Perkembangan Peserta Didik</h6>
            
            @foreach($template->items->where('is_bab', true)->sortBy('urutan') as $bab)
                @php 
                    // Cek apakah ada narasinya
                    $hasNarasi = $bab->anak_isian->where('tipe', 'textarea')->count() > 0;
                    $itemNarasi = $bab->anak_isian->whereIn('tipe', ['textarea', 'sub_judul'])->sortBy('urutan')->values(); 
                @endphp
                
                @if($hasNarasi)
                    <div class="mb-2 mt-3"><strong style="font-size: 13px;">{{ $bab->label }}</strong></div>
                    
                    @foreach($itemNarasi as $isian)
                        @if($isian->tipe == 'sub_judul')
                            <div class="mb-1 mt-2"><strong style="font-size: 12px; font-style: italic;">{{ $isian->label }}</strong></div>
                        @elseif($isian->tipe == 'textarea')
                            <div class="mb-3">
                                <strong style="font-size: 12px;">- {{ $isian->label }}</strong>
                                <p style="text-align: justify; line-height: 1.6; font-size: 12px; margin-top: 5px; white-space: pre-wrap;">{{ $nilai_json[$isian->id] ?? '-' }}</p>
                            </div>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- MUATAN LOKAL (LOOPING DARI TEMPLATE) --}}
        <div class="mb-4">
            <h6 class="fw-bold mb-2" style="font-size: 12px; text-transform: uppercase;">Penilaian Muatan Lokal</h6>
            <table class="table table-bordered table-sm small" style="font-size: 11px;">
                <thead style="background-color: #f0f0f0;">
                    <tr>
                        <th width="5%" class="text-center align-middle">No</th>
                        <th width="75%" class="text-center align-middle">Aspek Perkembangan</th>
                        <th width="20%" class="text-center align-middle">Hasil Penilaian</th>
                    </tr>
                </thead>
                <tbody>
                    @php $global_no = 1; @endphp
                    @foreach($template->items->where('is_bab', true)->sortBy('urutan') as $bab)
                        @php $itemMulok = $bab->anak_isian->where('tipe', 'radio'); @endphp
                        
                        @if($itemMulok->count() > 0)
                            <tr>
                                <td colspan="3" class="fw-bold" style="background-color: #f9f9f9;">{{ $bab->label }}</td>
                            </tr>
                            @foreach($itemMulok as $isian)
                                <tr>
                                    <td class="text-center">{{ $global_no++ }}</td>
                                    <td class="ps-4" style="white-space: normal;">{{ $isian->label }}</td>
                                    <td class="text-center fw-bold">{{ $nilai_json[$isian->id] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
            
            <div class="mt-2 text-muted" style="font-size: 10px;">
                *Keterangan Hasil Penilaian: 
                @if(is_array($template->opsi_mulok))
                    {{ implode(', ', $template->opsi_mulok) }}
                @endif
            </div>
        </div>

        {{-- FOOTER / TANDA TANGAN --}}
        <div class="row mt-5 small" style="margin-top: 50px;">
            <div class="col-4 text-center">
                <p style="margin-bottom: 60px; font-size: 12px;">Tanda Tangan Orang Tua/Wali</p>
                <p style="font-size: 11px;">( ................................... )</p>
            </div>
            <div class="col-4 text-center">
                <p style="margin-bottom: 60px; font-size: 12px;">Kepala Sekolah</p>
                <p class="fw-bold" style="font-size: 11px;">{{ auth()->user()->school->kepala_sekolah ?? '( ................................... )' }}</p>
            </div>
            <div class="col-4 text-center">
                <p style="margin-bottom: 5px; font-size: 11px;">Ditetapkan di: Malang</p>
                <p style="margin-bottom: 60px; font-size: 11px;">Tanggal: {{ date('d F Y') }}</p>
                <p class="fw-bold" style="font-size: 11px;">Guru Kelas</p>
                <p style="font-size: 11px;">{{ auth()->user()->name }}</p>
            </div>
        </div>
    @else
        <div class="text-center py-5 border rounded mt-4" style="border-style: dashed !important; border-color: #ccc !important;">
            <iconify-icon icon="solar:file-smile-bold-duotone" class="text-muted opacity-25" style="font-size: 80px;"></iconify-icon>
            <h5 class="mt-3 fw-bold text-dark">Rapor Belum Diisi / Template Kosong</h5>
            <p class="text-muted small">Data rapor untuk siswa ini pada semester berjalan belum diinput, atau template belum tersedia.</p>
        </div>
    @endif
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .rapor-paper, .rapor-paper * { visibility: visible; }
        .rapor-paper { 
            position: absolute; 
            left: 0; 
            top: 0; 
            width: 100%; 
            border: none !important; 
            box-shadow: none !important;
            padding: 40px !important;
            background: white !important;
        }
        .d-print-none { display: none !important; }
        html, body {
            margin: 0;
            padding: 0;
            background: white;
        }
    }
    .table-bordered th, .table-bordered td {
        border: 1px solid #000 !important;
        padding: 8px !important;
    }
    .rapor-paper {
        page-break-after: avoid;
    }
</style>
@endsection