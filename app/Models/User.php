<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\PengajuanAktifStudi;
use App\Models\PengajuanCuti;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'fakultas_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────────

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class);
    }

    public function pengajuanCutiProcessed(): HasMany
    {
        return $this->hasMany(PengajuanCuti::class, 'approved_by');
    }

    public function pengajuanAktifStudiProcessed(): HasMany
    {
        return $this->hasMany(PengajuanAktifStudi::class, 'approved_by');
    }

    // ─── Helpers ───────────────────────────────────────────────────

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdminAkademik(): bool
    {
        return $this->role === 'admin_akademik';
    }

    public function isAdminFakultas(): bool
    {
        return $this->role === 'admin_fakultas';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }
}
