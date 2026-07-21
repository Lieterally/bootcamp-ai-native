<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Fakultas extends Model
{
    use HasTranslations;

    protected $table = 'fakultas';

    protected $fillable = [
        'kode',
        'nama',
    ];

    public $translatable = ['nama'];

    // ─── Relationships ─────────────────────────────────────────────

    public function prodi(): HasMany
    {
        return $this->hasMany(Prodi::class);
    }

    public function admins(): HasMany
    {
        return $this->hasMany(User::class, 'fakultas_id');
    }
}
