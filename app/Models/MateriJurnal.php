<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriJurnal extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'school_id', 'kelompok', 'judul_materi', 'deskripsi', 'tanggal_dibuat'];

    public function jurnals() {
        return $this->hasMany(JurnalHarian::class, 'materi_id');
    }

    public static function syncFromLms(Materi $materi)
    {
        $kelompok = 'A';
        $kelasStr = (string)$materi->kelas;
        if (preg_match('/kelompok\s*([a-z0-9]+)/i', $kelasStr, $matches)) {
            $kelompok = strtoupper($matches[1]);
        } elseif (preg_match('/kelas\s*([a-z0-9]+)/i', $kelasStr, $matches)) {
            $kelompok = strtoupper($matches[1]);
        } elseif (in_array(strtoupper(trim($kelasStr)), ['A', 'B', 'PAUD'])) {
            $kelompok = strtoupper(trim($kelasStr));
        }

        if (!in_array($kelompok, ['A', 'B', 'PAUD'])) {
            $kelompok = 'A';
        }

        $tgl = $materi->created_at ? $materi->created_at->format('Y-m-d') : date('Y-m-d');

        return static::updateOrCreate(
            ['id' => $materi->id],
            [
                'school_id'      => $materi->school_id,
                'kelompok'       => $kelompok,
                'judul_materi'   => $materi->judul,
                'deskripsi'      => $materi->deskripsi,
                'tanggal_dibuat' => $tgl,
            ]
        );
    }

    public static function syncAll()
    {
        $allMateris = Materi::withoutGlobalScopes()->get();
        foreach ($allMateris as $materi) {
            static::syncFromLms($materi);
        }

        $allJurnals = static::all();
        foreach ($allJurnals as $mj) {
            Materi::withoutGlobalScopes()->updateOrCreate(
                ['id' => $mj->id],
                [
                    'school_id'      => $mj->school_id,
                    'judul'          => $mj->judul_materi,
                    'deskripsi'      => $mj->deskripsi,
                    'mata_pelajaran' => 'Kegiatan Jurnal',
                    'kelas'          => "Kelompok {$mj->kelompok}",
                    'created_by'     => \Illuminate\Support\Facades\Auth::id() ?? 1,
                    'status'         => 'Published'
                ]
            );
        }
    }
}