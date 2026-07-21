<?php

namespace App\Models;

use App\Models\PengajuanCuti;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';

    protected $fillable = [
        'user_id',
        'prodi_id',
        'nim',
        'name',
        'email',
        'semester_tempuh',
        'sks_tempuh',
        'sks_lulus',
        'dosen_wali',
        'status_akademik',
    ];

    protected function casts(): array
    {
        return [
            'semester_tempuh' => 'integer',
            'sks_tempuh' => 'integer',
            'sks_lulus' => 'integer',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function pengajuanCuti(): HasMany
    {
        return $this->hasMany(PengajuanCuti::class);
    }

    public function pengajuanAktifStudi(): HasMany
    {
        return $this->hasMany(PengajuanAktifStudi::class);
    }

    // ─── Helpers ───────────────────────────────────────────────────

    /**
     * Hitung jumlah cuti yang sudah disetujui.
     */
    public function jumlahCutiDisetujui(): int
    {
        return $this->pengajuanCuti()->where('status', 'Disetujui')->count();
    }

    /**
     * Cek apakah mahasiswa cuti di semester sebelumnya (berturut-turut).
     */
    public function cutiDiSemesterSebelumnya(PeriodeAkademik $periodeAktif): bool
    {
        $periodeBefore = PeriodeAkademik::where('id', '<', $periodeAktif->id)
            ->orderByDesc('id')
            ->first();

        if (!$periodeBefore) {
            return false;
        }

        return $this->pengajuanCuti()
            ->where('periode_akademik_id', $periodeBefore->id)
            ->where('status', 'Disetujui')
            ->exists();
    }

    /**
     * Cek apakah mahasiswa eligible untuk mengajukan cuti.
     */
    public function eligibleUntukCuti(): bool
    {
        return $this->semester_tempuh >= 2
            && $this->jumlahCutiDisetujui() < 2
            && $this->status_akademik === 'Aktif';
    }
}
