<?php

namespace App\Models;

use App\Models\PengajuanAktifStudi;
use App\Models\PengajuanCuti;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeAkademik extends Model
{
    protected $table = 'periode_akademik';

    protected $fillable = [
        'tahun_akademik',
        'semester',
        'is_active',
        'tanggal_buka_cuti',
        'tanggal_tutup_cuti',
        'tanggal_buka_aktif_studi',
        'tanggal_tutup_aktif_studi',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'tanggal_buka_cuti' => 'date',
            'tanggal_tutup_cuti' => 'date',
            'tanggal_buka_aktif_studi' => 'date',
            'tanggal_tutup_aktif_studi' => 'date',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────────

    public function pengajuanCuti(): HasMany
    {
        return $this->hasMany(PengajuanCuti::class);
    }

    public function pengajuanAktifStudi(): HasMany
    {
        return $this->hasMany(PengajuanAktifStudi::class);
    }

    // ─── Scopes & Helpers ──────────────────────────────────────────

    /**
     * Ambil periode akademik yang sedang aktif.
     */
    public static function aktif(): ?self
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Cek apakah saat ini dalam rentang pengajuan cuti.
     */
    public function dalamRentangCuti(): bool
    {
        $today = now()->toDateString();

        return $this->tanggal_buka_cuti
            && $this->tanggal_tutup_cuti
            && $today >= $this->tanggal_buka_cuti->toDateString()
            && $today <= $this->tanggal_tutup_cuti->toDateString();
    }

    /**
     * Cek apakah saat ini dalam rentang pengajuan aktif studi.
     */
    public function dalamRentangAktifStudi(): bool
    {
        $today = now()->toDateString();

        return $this->tanggal_buka_aktif_studi
            && $this->tanggal_tutup_aktif_studi
            && $today >= $this->tanggal_buka_aktif_studi->toDateString()
            && $today <= $this->tanggal_tutup_aktif_studi->toDateString();
    }
}
