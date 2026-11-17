<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatif';

    protected $fillable = [
        'nama_lokasi',
        'alamat',
    ];

    public function penilaians(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'alternatif_id');
    }

    public function hasilSpks(): HasMany
    {
        return $this->hasMany(HasilSpk::class, 'alternatif_id');
    }
}
