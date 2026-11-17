<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'alternatif_id',
        'kriteria_id',
        'nilai',
    ];

    protected $casts = [
        'nilai' => 'float',
    ];

    public function alternatif(): BelongsTo
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}
