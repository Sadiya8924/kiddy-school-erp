<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaporTemplate extends Model
{
    protected $fillable = ['nama_template', 'is_active', 'opsi_mulok'];

    protected $casts = [
        'opsi_mulok' => 'array',
    ];

    public function items() {
        return $this->hasMany(RaporTemplateItem::class, 'template_id');
    }
}