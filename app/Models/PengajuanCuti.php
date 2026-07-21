<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanCuti extends Model
{
    protected $table = 'pengajuan_cuti';

    protected $fillable = [
        'mahasiswa_id',
        'periode_akademik_id',
        'nim',
        'name',
        'prodi',
        'semester_tempuh',
        'sks_tempuh',
        'sks_lulus',
        'dosen_wali',
        'alasan_cuti',
        'status',
        'approved_by',
        'catatan',
        'submitted_at',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'semester_tempuh' => 'integer',
            'sks_tempuh' => 'integer',
            'sks_lulus' => 'integer',
            'submitted_at' => 'datetime',
            'processed_at' => 'datetime',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────────

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function periodeAkademik(): BelongsTo
    {
        return $this->belongsTo(PeriodeAkademik::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─── Helpers ───────────────────────────────────────────────────

    public function isMenungguPersetujuan(): bool
    {
        return $this->status === 'Menunggu Persetujuan';
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'Disetujui';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'Ditolak';
    }
}
