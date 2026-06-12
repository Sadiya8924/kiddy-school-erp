<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaporTemplateItem extends Model
{
    protected $fillable = ['template_id', 'parent_id', 'is_bab', 'label', 'tipe', 'opsi_pilihan', 'urutan'];

    protected $casts = [
        'opsi_pilihan' => 'array',
    ];

    // Relasi untuk memanggil "Isian" di dalam sebuah "BAB"
    public function anak_isian() {
        return $this->hasMany(RaporTemplateItem::class, 'parent_id')->orderBy('urutan');
    }
}