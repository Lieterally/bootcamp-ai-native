<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanAktifStudi extends Model
{
    protected $table = 'pengajuan_aktif_studi';

    protected $fillable = [
        'mahasiswa_id',
        'periode_akademik_id',
        'file_khs',
        'file_bukti_ukt',
        'status',
        'approved_by',
        'catatan',
        'submitted_at',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
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
