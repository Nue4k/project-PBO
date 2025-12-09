# 4. Controllers & API (Contoh Request/Response)

Dokumen ini memberikan contoh nyata format JSON yang dikirim dan diterima oleh Controller.

---

## 1. Register (`POST /api/register`)

**Tujuan**: Mendaftarkan pengguna baru.

### Request Body (Kirim)
```json
{
  "email": "mahasiswa@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "student"
}
```

### Response (Terima)
```json
{
  "user": {
    "id": "9s8d-f7g6-...",
    "email": "mahasiswa@example.com",
    "role": "student",
    "created_at": "2024-01-01T10:00:00.000000Z"
  },
  "token": "1|LaravelSanctumTokenString..."
}
```

---

## 2. Get Jobs (`GET /api/jobs`)

**Tujuan**: Mengambil daftar lowongan untuk ditampilkan di halaman depan.

### Response
```json
{
  "data": [
    {
      "id": "job-uuid-1",
      "title": "Backend Developer",
      "company": {
        "company_name": "Tech Corp",
        "logo_url": "..."
      },
      "job_type": "wfh",
      "location": "Jakarta",
      "closing_date": "2024-12-31"
    },
    {
      "id": "job-uuid-2",
      "title": "Frontend Developer",
      ...
    }
  ]
}
```

---

## 3. Apply Job (`POST /api/applications`)

**Tujuan**: Mahasiswa melamar pekerjaan.

### Request Body
```json
{
  "job_id": "job-uuid-1",
  "cover_letter": "Saya sangat tertarik dengan posisi ini karena..."
}
```
*(Catatan: `student_id` diambil otomatis dari Token login)*

### Response Sukses
```json
{
  "message": "Application submitted successfully",
  "data": {
    "id": "app-uuid-new",
    "status": "applied",
    "created_at": "..."
  }
}
```

### Response Gagal (Jika Duplikat)
```json
{
  "message": "Server Error", 
  "error": "You have already applied for this job."
}
```

---

## 4. Validasi Form (`Requests`)
Kami menggunakan class Validasi terpisah untuk menjaga Controller tetap bersih.

User input **wajib** memenuhi aturan ini sebelum diproses:
*   **RegisterRequest**: `email` (unique), `password` (min 8 char).
*   **DocumentUploadRequest**: `file` (max 10MB, types: pdf,doc).
*   **JobCreateRequest**: `title` (required), `requirements` (required).
