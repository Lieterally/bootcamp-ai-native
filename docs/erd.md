# ERD - Sistem Cuti Mahasiswa & Aktif Studi

```mermaid
erDiagram
    users {
        bigint id PK
        string name
        string email UK
        string password
        string role "superadmin|admin_akademik|admin_fakultas|mahasiswa"
        bigint fakultas_id FK "nullable, for admin_fakultas"
        timestamp created_at
        timestamp updated_at
    }

    fakultas {
        bigint id PK
        string kode UK "max 10 chars"
        string nama "max 100 chars"
        timestamp created_at
        timestamp updated_at
    }

    prodi {
        bigint id PK
        bigint fakultas_id FK
        string kode UK "max 10 chars"
        string nama "max 100 chars"
        string jenjang "S1|S2|S3|D3|D4"
        timestamp created_at
        timestamp updated_at
    }

    mahasiswa {
        bigint id PK
        bigint user_id FK
        bigint prodi_id FK
        string nim UK "max 20 chars"
        string name "max 100 chars"
        string email "max 100 chars"
        integer semester_tempuh "1-14"
        integer sks_tempuh "0-160"
        integer sks_lulus "0-160, <= sks_tempuh"
        string dosen_wali "max 100 chars"
        enum status_akademik "Aktif|Cuti|Mengundurkan Diri"
        timestamp created_at
        timestamp updated_at
    }

    periode_akademik {
        bigint id PK
        string tahun_akademik "format YYYY/YYYY"
        enum semester "Ganjil|Genap"
        boolean is_active "unique active constraint"
        date tanggal_buka_cuti
        date tanggal_tutup_cuti
        date tanggal_buka_aktif_studi
        date tanggal_tutup_aktif_studi
        timestamp created_at
        timestamp updated_at
    }

    pengajuan_cuti {
        bigint id PK
        bigint mahasiswa_id FK
        bigint periode_akademik_id FK
        string nim
        string name
        string prodi
        integer semester_tempuh
        integer sks_tempuh
        integer sks_lulus
        string dosen_wali
        text alasan_cuti "10-500 chars"
        enum status "Menunggu Persetujuan|Disetujui|Ditolak"
        bigint approved_by FK "nullable"
        string catatan "max 500 chars, nullable"
        timestamp submitted_at
        timestamp processed_at "nullable"
        timestamp created_at
        timestamp updated_at
    }

    pengajuan_aktif_studi {
        bigint id PK
        bigint mahasiswa_id FK
        bigint periode_akademik_id FK
        string file_khs "path, PDF/JPG/PNG max 2MB"
        string file_bukti_ukt "path, PDF/JPG/PNG max 2MB"
        enum status "Menunggu Persetujuan|Disetujui|Ditolak"
        bigint approved_by FK "nullable"
        string catatan "max 500 chars, nullable"
        timestamp submitted_at
        timestamp processed_at "nullable"
        timestamp created_at
        timestamp updated_at
    }

    fakultas ||--o{ prodi : "has many"
    fakultas ||--o{ users : "admin_fakultas belongs to"
    prodi ||--o{ mahasiswa : "has many"
    users ||--o| mahasiswa : "has profile"
    mahasiswa ||--o{ pengajuan_cuti : "submits"
    mahasiswa ||--o{ pengajuan_aktif_studi : "submits"
    periode_akademik ||--o{ pengajuan_cuti : "belongs to"
    periode_akademik ||--o{ pengajuan_aktif_studi : "belongs to"
    users ||--o{ pengajuan_cuti : "processes (approved_by)"
    users ||--o{ pengajuan_aktif_studi : "processes (approved_by)"
```

## Penjelasan Relasi

| Relasi | Keterangan |
|--------|-----------|
| `fakultas` → `prodi` | One-to-many. Satu fakultas memiliki banyak program studi |
| `fakultas` → `users` (admin_fakultas) | One-to-many. Admin fakultas terikat ke satu fakultas |
| `prodi` → `mahasiswa` | One-to-many. Satu prodi memiliki banyak mahasiswa |
| `users` → `mahasiswa` | One-to-one. Setiap mahasiswa punya 1 user account |
| `mahasiswa` → `pengajuan_cuti` | One-to-many. Mahasiswa bisa punya banyak riwayat cuti |
| `mahasiswa` → `pengajuan_aktif_studi` | One-to-many. Mahasiswa bisa punya banyak riwayat aktif studi |
| `periode_akademik` → `pengajuan_cuti` | One-to-many. Setiap pengajuan terikat 1 periode |
| `periode_akademik` → `pengajuan_aktif_studi` | One-to-many. Setiap pengajuan terikat 1 periode |
| `users` → `pengajuan_cuti` (approved_by) | Admin yang memproses pengajuan |
| `users` → `pengajuan_aktif_studi` (approved_by) | Admin yang memproses pengajuan |

## Constraint Penting

- `fakultas.kode`: unique
- `prodi.kode`: unique
- `periode_akademik`: unique composite pada (`tahun_akademik`, `semester`)
- `mahasiswa.nim`: unique
- `mahasiswa.sks_lulus` <= `mahasiswa.sks_tempuh`
- `pengajuan_cuti`: max 2 record dengan status "Disetujui" per mahasiswa
- `pengajuan_cuti`: tidak boleh ada 2 record "Disetujui" di periode berturutan
- `users` dengan role `admin_fakultas` wajib memiliki `fakultas_id`
- Admin fakultas hanya dapat memproses pengajuan dari mahasiswa di fakultas yang sama
