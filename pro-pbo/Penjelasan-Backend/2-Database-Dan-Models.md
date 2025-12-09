# 2. Database dan Models (Detail Skema)

Dokumen ini menjelaskan spesifikasi teknis database, termasuk Tipe Data dan Constraint.

---

## 1. User (`users`)
Tabel utama untuk otentikasi.

| Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| **`id`** | `UUID` (String 36) | **Primary Key** | ID Acak Unik (Contoh: `9a7b...`). |
| **`email`** | `String` (255) | **Unique**, Not Null | Alamat login. |
| **`password`** | `String` (255) | Not Null | Password ter-hash (Bcrypt). |
| **`role`** | `Enum` | Not Null | Pilihan: `'student'`, `'company'`, `'admin'`. |
| `created_at` | `Timestamp` | Nullable | Tanggal daftar. |

## 2. StudentProfile (`student_profiles`)
Detail biodata mahasiswa.

| Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| **`id`** | `UUID` | **PK** | ID Profil. |
| **`user_id`** | `UUID` | **Foreign Key** | Relasi ke tabel `users`. |
| **`full_name`** | `String` | Nullable | Nama Lengkap. |
| **`university`** | `String` | Nullable | Nama Kampus. |
| **`major`** | `String` | Nullable | Jurusan (misal: "Informatika"). |
| **`gpa`** | `Float` (3,2) | Nullable | IPK (misal: 3.85). |
| **`status`** | `Enum` | Default: `active` | Pilihan: `'active'`, `'hired'`. |
| **`skills`** | `JSON` | Nullable | Array skill: `["PHP", "Laravel"]`. |
| **`experience`** | `JSON` | Nullable | Riwayat kerja (Struktur bebas). |
| **`resume`** | `String` | Nullable | Path file default. |

## 3. CompanyProfile (`company_profiles`)
Detail biodata perusahaan.

| Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| **`id`** | `UUID` | **PK** | ID Profil. |
| **`user_id`** | `UUID` | **FK** | Relasi ke tabel `users`. |
| **`company_name`** | `String` | Nullable | Nama Perusahaan. |
| **`industry`** | `String` | Nullable | Industri (misal: "Fintech"). |
| **`description`** | `Text` | Nullable | Deskripsi panjang. |
| **`website_url`** | `String` | Nullable | Link website. |

## 4. Job (`jobs`)
Data lowongan kerja.

| Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| **`id`** | `UUID` | **PK** | ID Lowongan. |
| **`company_id`** | `UUID` | **FK** | Relasi ke `company_profiles`. |
| **`title`** | `String` | Not Null | Judul pekerjaan. |
| **`requirements`** | `JSON` | Not Null | Syarat detail (Format fleksibel). |
| **`job_type`** | `Enum` | Not Null | `'wfo'`, `'wfh'`, `'hybrid'`. |
| **`is_active`** | `Boolean` | Default: `true` | `1`=Aktif, `0`=Tutup. |
| **`closing_date`** | `Date` | Not Null | Batas akhir lamaran. |

## 5. Application (`applications`)
Transaksi lamaran.

| Kolom | Tipe Data | Constraint | Keterangan |
| :--- | :--- | :--- | :--- |
| **`id`** | `UUID` | **PK** | ID Lamaran. |
| **`job_id`** | `UUID` | **FK** | Relasi ke `jobs`. |
| **`student_id`** | `UUID` | **FK** | Relasi ke `student_profiles`. |
| **`resume_id`** | `UUID` | **FK** | Relasi ke `documents`. |
| **`status`** | `Enum` | Default: `applied` | `'applied'`, `'interview'`, `'accepted'`, `'rejected'`. |
| **`cover_letter`** | `Text` | Not Null | Pesan pengantar. |

---

## Catatan Teknis
1.  **Mengapa JSON?**: Kolom seperti `skills` dan `requirements` menggunakan JSON agar kita bisa menyimpan data list (daftar) tanpa harus membuat tabel baru (Normalize).
2.  **Mengapa UUID?**: Agar ID tidak bisa ditebak (Sequential Attack Prevention).
