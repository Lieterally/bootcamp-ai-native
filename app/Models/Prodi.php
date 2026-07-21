<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Prodi extends Model
{
    use HasTranslations;

    protected $table = 'prodi';

    protected $fillable = [
        'fakultas_id',
        'kode',
        'nama',
        'jenjang',
    ];

    public $translatable = ['nama'];

    // ─── Relationships ─────────────────────────────────────────────

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function mahasiswa(): HasMany
    {
        return $this->hasMany(Mahasiswa::class);
    }
}
