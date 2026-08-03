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

<div class="rapor-print-container">
    
    {{-- HALAMAN 1: BIODATA SISWA --}}
    <div class="rapor-paper page-biodata shadow-sm mx-auto bg-white border mb-4">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-uppercase title-playful" style="letter-spacing: 1px; font-size: 24px; text-decoration: underline;">BIODATA SISWA</h3>
        </div>

        <table class="table table-bordered border-dark align-middle mb-4" style="font-size: 14px;">
            <tbody>
                <tr>
                    <td width="35%" class="fw-bold px-3 py-2 text-uppercase">1. Nama Siswa &nbsp;&nbsp;&nbsp;&nbsp; A. Lengkap</td>
                    <td class="px-3 py-2 fw-bold">{{ $siswa->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; B. Panggilan</td>
                    <td class="px-3 py-2">-</td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">2. Nomor Induk</td>
                    <td class="px-3 py-2">{{ $siswa->no_induk ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">3. Tempat, Tanggal Lahir</td>
                    <td class="px-3 py-2">{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tgl_lahir ? \Carbon\Carbon::parse($siswa->tgl_lahir)->translatedFormat('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">4. Jenis Kelamin</td>
                    <td class="px-3 py-2">{{ $siswa->jenis_kelamin ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">5. Agama</td>
                    <td class="px-3 py-2">{{ $siswa->agama ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">6. Anak Ke / Jumlah Saudara</td>
                    <td class="px-3 py-2">
                        @if($siswa->anak_ke)
                            {{ $siswa->anak_ke }}
                        @else
                            -
                        @endif
                        / 
                        @if($siswa->jml_saudara)
                            {{ $siswa->jml_saudara }} Saudara
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">7. Bahasa Sehari-hari</td>
                    <td class="px-3 py-2">Bahasa Indonesia</td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">8. Alamat Lengkap</td>
                    <td class="px-3 py-2">
                        {{ $siswa->alamat ?? '-' }}
                        @if($siswa->rt || $siswa->rw)
                            RT {{ $siswa->rt ?? '-' }} / RW {{ $siswa->rw ?? '-' }}
                        @endif
                        @if($siswa->dusun)
                            , Dusun {{ $siswa->dusun }}
                        @endif
                        @if($siswa->kelurahan)
                            , Desa/Kel. {{ $siswa->kelurahan }}
                        @endif
                        @if($siswa->kecamatan)
                            , Kec. {{ $siswa->kecamatan }}
                        @endif
                        @if($siswa->kode_pos)
                            , {{ $siswa->kode_pos }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">9. Data Ayah</td>
                    <td class="px-3 py-2 bg-light"></td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">&nbsp;&nbsp;&nbsp;&nbsp; A. Nama</td>
                    <td class="px-3 py-2">{{ $siswa->nama_ayah ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">&nbsp;&nbsp;&nbsp;&nbsp; B. Pekerjaan</td>
                    <td class="px-3 py-2">{{ $siswa->pekerjaan_ayah ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">&nbsp;&nbsp;&nbsp;&nbsp; C. No. Telp / HP</td>
                    <td class="px-3 py-2">{{ $siswa->hp ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">10. Data Ibu</td>
                    <td class="px-3 py-2 bg-light"></td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">&nbsp;&nbsp;&nbsp;&nbsp; A. Nama</td>
                    <td class="px-3 py-2">{{ $siswa->nama_ibu ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">&nbsp;&nbsp;&nbsp;&nbsp; B. Pekerjaan</td>
                    <td class="px-3 py-2">{{ $siswa->pekerjaan_ibu ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold px-3 py-2 text-uppercase">&nbsp;&nbsp;&nbsp;&nbsp; C. No. Telp / HP</td>
                    <td class="px-3 py-2">{{ $siswa->hp ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-end mt-5 pt-3">
            <div class="text-center" style="width: 140px;">
                <div class="border border-dark rounded p-1 bg-light d-flex align-items-center justify-content-center" style="width: 120px; height: 160px;">
                    @if($siswa->foto_anak)
                        <img src="/storage/{{ $siswa->foto_anak }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span class="text-muted small">Foto 3x4</span>
                    @endif
                </div>
            </div>
            
            <div class="text-center" style="width: 300px; font-family: 'Comic Sans MS', sans-serif;">
                <p class="mb-1 fw-bold text-uppercase">Mengetahui</p>
                <p class="mb-5 fw-bold text-uppercase">Kepala Sekolah</p>
                @php
                    $kepsekName = auth()->user()->school->kepala_sekolah ?? 'Meta Andri Setiawan, S.Pd';
                @endphp
                <p class="fw-bold mb-0" style="text-decoration: underline; font-size: 15px;">{{ $kepsekName }}</p>
            </div>
        </div>
    </div>

    {{-- PAGE BREAK FOR PRINTING --}}
    <div class="page-break"></div>

    {{-- HALAMAN 2: LAPORAN PERKEMBANGAN --}}
    <div class="rapor-paper page-evaluasi shadow-sm mx-auto bg-white border">
        
        {{-- HEADER EVALUASI --}}
        <div class="header-evaluasi mb-4" style="font-family: 'Comic Sans MS', sans-serif; font-size: 16px;">
            <div class="row mb-1">
                <div class="col-4 fw-bold text-uppercase">SEMESTER</div>
                <div class="col-8 fw-bold">: {{ $rapor?->semester == 'Ganjil' ? '1' : ($rapor?->semester == 'Genap' ? '2' : $rapor?->semester) }} - {{ strtoupper($siswa->nama_kelas_aktif) }}</div>
            </div>
            <div class="row">
                <div class="col-4 fw-bold text-uppercase">TAHUN AJARAN</div>
                <div class="col-8 fw-bold">: {{ $rapor?->tahun_ajaran ?? '-' }}</div>
            </div>
        </div>

        <hr style="border-top: 2px solid #000; margin: 15px 0;">

        @if($rapor && $template)
            {{-- BAGIAN NARASI PERKEMBANGAN --}}
            <div class="mb-4">
                <h5 class="fw-bold mb-3 text-uppercase title-playful" style="font-size: 15px; text-decoration: underline;">Laporan Perkembangan Peserta Didik</h5>
                
                @foreach($template->items->where('is_bab', true)->sortBy('urutan') as $bab)
                    @php 
                        $hasNarasi = $bab->anak_isian->where('tipe', 'textarea')->count() > 0;
                        $itemNarasi = $bab->anak_isian->whereIn('tipe', ['textarea', 'sub_judul'])->sortBy('urutan')->values(); 
                    @endphp
                    
                    @if($hasNarasi)
                        <div class="mb-2 mt-3"><strong style="font-size: 14px; text-transform: uppercase;">{{ $bab->label }}</strong></div>
                        
                        @foreach($itemNarasi as $isian)
                            @if($isian->tipe == 'sub_judul')
                                <div class="mb-1 mt-2"><strong style="font-size: 13px; font-style: italic; color: #444;">{{ $isian->label }}</strong></div>
                            @elseif($isian->tipe == 'textarea')
                                <div class="mb-3 ps-3">
                                    <strong style="font-size: 13px;">- {{ $isian->label }}</strong>
                                    <p style="text-align: justify; line-height: 1.6; font-size: 13px; margin-top: 5px; white-space: pre-wrap;">{{ $nilai_json[$isian->id] ?? '-' }}</p>
                                </div>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- PENILAIAN MUATAN LOKAL --}}
            <div class="mb-4">
                <h5 class="fw-bold mb-3 text-uppercase title-playful" style="font-size: 15px; text-decoration: underline;">Penilaian Muatan Lokal</h5>
                <table class="table table-bordered border-dark table-sm" style="font-size: 12px;">
                    <thead class="bg-light text-center fw-bold">
                        <tr>
                            <th width="5%" class="align-middle">No</th>
                            <th width="75%" class="align-middle">Aspek Perkembangan</th>
                            <th width="20%" class="align-middle">Hasil Penilaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $global_no = 1; @endphp
                        @foreach($template->items->where('is_bab', true)->sortBy('urutan') as $bab)
                            @php $itemMulok = $bab->anak_isian->where('tipe', 'radio'); @endphp
                            
                            @if($itemMulok->count() > 0)
                                <tr>
                                    <td colspan="3" class="fw-bold bg-light ps-2">{{ $bab->label }}</td>
                                </tr>
                                @foreach($itemMulok as $isian)
                                    <tr>
                                        <td class="text-center">{{ $global_no++ }}</td>
                                        <td class="ps-3">{{ $isian->label }}</td>
                                        <td class="text-center fw-bold">{{ $nilai_json[$isian->id] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
                
                <div class="mt-2 text-muted" style="font-size: 11px;">
                    *Keterangan Hasil Penilaian: 
                    @if(is_array($template->opsi_mulok))
                        {{ implode(', ', $template->opsi_mulok) }}
                    @endif
                </div>
            </div>

            {{-- DATA FISIK & KEHADIRAN --}}
            <div class="mb-4">
                <h5 class="fw-bold mb-3 text-uppercase title-playful" style="font-size: 15px; text-decoration: underline;">Data Fisik & Kehadiran</h5>
                <div class="row text-center small g-0" style="border: 1px solid #000;">
                    <div class="col-2 border-end border-dark py-2">
                        <div class="text-muted" style="font-size: 11px;">Tinggi Badan</div>
                        <div class="fw-bold" style="font-size: 13px;">{{ $rapor?->tinggi_badan ?? '-' }} cm</div>
                    </div>
                    <div class="col-2 border-end border-dark py-2">
                        <div class="text-muted" style="font-size: 11px;">Berat Badan</div>
                        <div class="fw-bold" style="font-size: 13px;">{{ $rapor?->berat_badan ?? '-' }} kg</div>
                    </div>
                    <div class="col-2 border-end border-dark py-2">
                        <div class="text-muted" style="font-size: 11px;">Sakit</div>
                        <div class="fw-bold" style="font-size: 13px;">{{ $rapor?->sakit ?? '0' }} Hari</div>
                    </div>
                    <div class="col-2 border-end border-dark py-2">
                        <div class="text-muted" style="font-size: 11px;">Izin</div>
                        <div class="fw-bold" style="font-size: 13px;">{{ $rapor?->izin ?? '0' }} Hari</div>
                    </div>
                    <div class="col-2 py-2">
                        <div class="text-muted" style="font-size: 11px;">Alfa</div>
                        <div class="fw-bold" style="font-size: 13px;">{{ $rapor?->alfa ?? '0' }} Hari</div>
                    </div>
                </div>
            </div>

            {{-- TANDA TANGAN --}}
            <div class="row mt-5" style="font-size: 13px; font-family: 'Comic Sans MS', sans-serif;">
                <div class="col-4 text-center">
                    <p class="mb-5">Tanda Tangan Orang Tua/Wali</p>
                    <p class="mb-0">( ................................... )</p>
                </div>
                <div class="col-4 text-center">
                    <p class="mb-5">Kepala Sekolah</p>
                    <p class="fw-bold mb-0" style="text-decoration: underline;">{{ $kepsekName }}</p>
                </div>
                <div class="col-4 text-center">
                    <p class="mb-1">Malang, {{ date('d F Y') }}</p>
                    <p class="mb-5">Guru Kelas</p>
                    <p class="fw-bold mb-0" style="text-decoration: underline;">{{ auth()->user()->name }}</p>
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

    {{-- LAMPIRAN FOTO DOKUMENTASI KEGIATAN SISWA --}}
    @php
        $fotoKegiatanList = [];
        if ($rapor && !empty($rapor->foto_kegiatan)) {
            $fotoKegiatanList = is_string($rapor->foto_kegiatan) ? json_decode($rapor->foto_kegiatan, true) : $rapor->foto_kegiatan;
        }
    @endphp

    @if(!empty($fotoKegiatanList) && is_array($fotoKegiatanList))
        <div class="page-break"></div>
        <div class="rapor-paper shadow-sm mx-auto bg-white border mb-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-uppercase title-playful" style="letter-spacing: 1px; font-size: 22px; text-decoration: underline;">DOKUMENTASI FOTO KEGIATAN SISWA</h3>
                <p class="text-muted small">Lampiran Foto Kegiatan Terbaik Ananda <strong>{{ $siswa->nama_lengkap }}</strong></p>
            </div>

            <div class="row row-cols-2 g-4 my-3">
                @foreach($fotoKegiatanList as $fotoPath)
                    @php
                        $filename = basename($fotoPath);
                        $url = str_starts_with($fotoPath, 'jurnal_media/foto/') 
                            ? route('jurnal.media', ['type' => 'foto', 'filename' => $filename]) 
                            : asset('storage/' . $fotoPath);
                    @endphp
                    <div class="col text-center">
                        <div class="p-2 border rounded-3 bg-light shadow-sm">
                            <img src="{{ $url }}" class="img-fluid rounded-2 object-fit-cover w-100" style="max-height: 250px;" alt="Dokumentasi Kegiatan">
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- TANDA TANGAN DI HALAMAN LAMPIRAN --}}
            <div class="row mt-5" style="font-size: 13px; font-family: 'Comic Sans MS', sans-serif;">
                <div class="col-4 text-center">
                    <p class="mb-5">Tanda Tangan Orang Tua/Wali</p>
                    <p class="mb-0">( ................................... )</p>
                </div>
                <div class="col-4 text-center">
                    <p class="mb-5">Kepala Sekolah</p>
                    <p class="fw-bold mb-0" style="text-decoration: underline;">{{ $kepsekName }}</p>
                </div>
                <div class="col-4 text-center">
                    <p class="mb-1">Malang, {{ date('d F Y') }}</p>
                    <p class="mb-5">Guru Kelas</p>
                    <p class="fw-bold mb-0" style="text-decoration: underline;">{{ auth()->user()->name }}</p>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .rapor-paper {
        max-width: 900px;
        color: #000;
        font-family: 'Comic Sans MS', 'Outfit', 'Inter', sans-serif;
        padding: 50px 60px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-radius: 4px;
        min-height: 1100px;
    }
    
    .title-playful {
        font-family: 'Comic Sans MS', 'Outfit', 'Inter', sans-serif;
        color: #1a1a1a;
    }

    .table-bordered th, .table-bordered td {
        border: 1px solid #000 !important;
        padding: 10px !important;
    }

    @media print {
        body * { visibility: hidden; }
        .rapor-print-container, .rapor-print-container * { visibility: visible; }
        .rapor-print-container { 
            position: absolute; 
            left: 0; 
            top: 0; 
            width: 100%; 
            background: white !important;
        }
        .rapor-paper {
            border: none !important;
            box-shadow: none !important;
            padding: 20px 30px !important;
            width: 100% !important;
            max-width: 100% !important;
            min-height: auto !important;
            page-break-after: always;
            break-after: page;
        }
        .page-biodata {
            page-break-after: always;
            break-after: page;
        }
        .page-break {
            display: block;
            page-break-before: always;
            break-before: page;
        }
        .d-print-none { display: none !important; }
        html, body {
            margin: 0;
            padding: 0;
            background: white;
        }
    }
</style>
@endsection