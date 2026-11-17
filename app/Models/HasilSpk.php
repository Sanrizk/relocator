<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilSpk extends Model
{
    use HasFactory;

    protected $table = 'hasil_spk';

    protected $fillable = [
        'alternatif_id',
        'nilai_preferensi',
        'ranking',
    ];

    protected $casts = [
        'nilai_preferensi' => 'float',
        'ranking' => 'integer',
    ];

    public function alternatif(): BelongsTo
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id');
    }
}
