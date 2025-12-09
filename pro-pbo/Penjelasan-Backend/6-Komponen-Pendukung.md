# 6. Komponen Pendukung Lainnya

Selain MVC (Model-View-Controller), Laravel memiliki komponen pendukung yang membuat aplikasi berjalan lancar.

---

## 1. Middleware (`app/Http/Middleware`)
Middleware adalah "pos pemeriksaan" sebelum Controller.

### a. `auth:sanctum` (Bawaan Laravel)
*   Mengecek Header `Authorization: Bearer <token>`.
*   Jika token valid, sistem mengenali siapa user ini (`Auth::user()`).
*   Jika token salah, langsung stop dan error 401.

### b. `CorsMiddleware.php`
*   Menangani **Cross-Origin Resource Sharing**.
*   Mengizinkan Frontend (Next.js) yang berjalan di `localhost:3000` untuk mengakses Backend di `localhost:8000`. Tanpa ini, browser akan memblokir request.

---

## 2. Service Provider (`app/Providers/AppServiceProvider.php`)
Ini adalah tempat "pendaftaran" (bootstrapping) aplikasi.

### Fungsi Utama: Binding Interface
Di file ini, kita mendaftarkan hubungan antara **Interface** dan **Class Service Asli**.

```php
// Contoh Kode
$this->app->bind(AuthServiceInterface::class, AuthService::class);
```
**Artinya**: "Hei Laravel, kalau nanti ada Controller yang minta `AuthServiceInterface`, tolong kasihkan dia `AuthService` yang asli."
*   Teknik ini disebut **Dependency Injection**.
*   Berguna agar kode Controller tidak terikat mati dengan satu file Service saja (mudah diganti-ganti nanti).

---

## 3. Storage (`storage/app/public`)
Tempat penyimpanan file fisik.
*   File dokumen tidak disimpan di folder public biasa (agar tidak bisa diakses orang lewat link langsung).
*   File disimpan di folder storage yang terproteksi, lalu dilayani lewat `DocumentController`.
