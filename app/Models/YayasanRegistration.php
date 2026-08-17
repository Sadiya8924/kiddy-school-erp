<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YayasanRegistration extends Model
{
    use HasFactory;

    protected $table = 'yayasan_registrations';

    protected $fillable = [
        'nama_yayasan',
        'kode_yayasan',
        'penanggung_jawab',
        'email_kontak',
        'telepon',
        'alamat',
        'lintang',
        'bujur',
        'nomor_akta_pendirian',
        'nomor_sk_kemenkumham',
        'npwp_yayasan',
        'status',
        'catatan_owner',
        'diproses_at',
    ];

    protected $casts = [
        'diproses_at' => 'datetime',
        'lintang'     => 'decimal:7',
        'bujur'       => 'decimal:7',
    ];

    public function isMenunggu(): bool { return $this->status === 'menunggu'; }
    public function isDisetujui(): bool { return $this->status === 'disetujui'; }
    public function isDitolak(): bool { return $this->status === 'ditolak'; }
}
