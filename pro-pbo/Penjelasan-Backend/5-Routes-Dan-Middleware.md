# 5. Routes & Middleware (Jalur Akses)

Dokumen ini menjelaskan pengaturan alamat URL (Routes) dan keamanan akses.

---

## Pengaturan Route
Route menentukan alamat URL apa saja yang bisa diakses oleh aplikasi luar. Daftar route ada di file `routes/api.php`.

Kami membagi route menjadi 2 kelompok berdasarkan keamanan:

### 1. Route Publik (Bebas Akses)
URL ini bisa diakses oleh siapa saja tanpa harus login.
*   `POST /register`: Untuk mendaftar akun baru.
*   `POST /login`: Untuk masuk ke aplikasi.
*   `GET /jobs`: Untuk melihat daftar lowongan kerja (agar bisa dilihat umum).

### 2. Route Terproteksi (Wajib Login)
URL ini dilindungi oleh sistem keamanan (Middleware). Hanya pengguna yang sudah login dan memiliki **Token** yang valid yang bisa mengaksesnya.

```php
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', ...);
    
    // Lihat Profil Sendiri
    Route::get('/profile/student', ...);
    
    // Kirim Lamaran (Hanya bisa jika sudah login)
    Route::post('/applications', ...);
});
```

---

## Middleware (Keamanan)
Middleware adalah sistem pengecekan otomatis yang berjalan sebelum request sampai ke Controller.

*   Tugas Middleware: Mengecek apakah pengguna menyertakan "Kunci Akses" (Token) yang benar pada setiap request.
*   Jika Token valid -> Request diteruskan ke Controller.
*   Jika Token tidak ada/salah -> Request ditolak dengan pesan "Unauthorized" (Tidak Diizinkan).

---

## Standar API
Kami mengikuti standar web umum untuk penamaan URL:
*   URL diawali dengan `/api`.
*   Menggunakan format JSON untuk pertukaran data.
*   Menggunakan metode HTTP yang sesuai:
    *   `GET` untuk mengambil data.
    *   `POST` untuk mengirim data baru.
    *   `PUT` untuk memperbarui data.
    *   `DELETE` untuk menghapus data.
