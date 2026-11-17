<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'nama_kriteria',
        'jenis',
        'peringkat',
        'bobot_roc',
    ];

    protected $casts = [
        'peringkat' => 'integer',
        'bobot_roc' => 'float',
    ];

    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'kriteria_id');
    }
}
