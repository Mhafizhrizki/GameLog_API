# 📄 PRD Backend — GameLog Tracker Core API

> **Kelompok 10** | Mata Kuliah: Pemrograman Berbasis Web / Framework  
> **Repositori:** https://github.com/Mhafizhrizki/GameLog_API  
> **Tanggal:** 10 Juni 2026  
> **Versi Dokumen:** 1.0.0

---

## 1. Latar Belakang & Gambaran Proyek

**GameLog Tracker** adalah aplikasi web yang memungkinkan pengguna untuk mencari game dari katalog global dan melacak status permainan mereka secara pribadi (Wishlist, Playing, Completed).

Sistem menggunakan arsitektur **Decoupled (terpisah)**:
- **Frontend** (React.js) → mengonsumsi RAWG.io API untuk katalog game & memanggil Backend untuk operasi CRUD data pengguna.
- **Backend** (Laravel) → berfokus pada autentikasi pengguna, logika bisnis, dan persistensi data ke database MySQL.

Dokumen ini secara khusus mendefinisikan kebutuhan, spesifikasi, dan batasan pengembangan **sisi Backend** (Laravel).

---

## 2. Tujuan Produk Backend

| # | Tujuan |
|---|--------|
| 1 | Menyediakan sistem **autentikasi** yang aman (Register & Login) berbasis token (Laravel Sanctum). |
| 2 | Menyediakan endpoint **CRUD** (Create, Read, Update, Delete) untuk data Game Tracker pengguna. |
| 3 | Memastikan setiap data tracker **terisolasi per pengguna** (user tidak dapat mengakses/mengubah data milik pengguna lain). |
| 4 | Mengembalikan **respons JSON yang konsisten** dan terstandarisasi untuk dikonsumsi oleh Frontend. |
| 5 | Menjaga **keamanan endpoint** sehingga hanya request dengan token valid yang dapat mengakses data. |

---

## 3. Tim & Tanggung Jawab

| Peran | Anggota | NIM | Teknologi |
|-------|---------|-----|-----------|
| **Backend & Dev** | Achmad Faris Faqih | 230705001 | Laravel |
| Frontend & Dev | M. Hafiz Rizky | 2307050212 | React |

---

## 4. Stack Teknologi Backend

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| **Bahasa** | PHP | ^8.3 |
| **Framework** | Laravel | ^13.8 |
| **Autentikasi** | Laravel Sanctum | ^4.0 |
| **Database** | MySQL | (menyesuaikan environment) |
| **ORM** | Eloquent (bawaan Laravel) | — |
| **DevOps / CI** | GitHub Actions | — |

---

## 5. Arsitektur Sistem

```
┌──────────────────────────────────────────────────────────────┐
│                     CLIENT (Browser)                          │
└────────────────────────┬─────────────────────────────────────┘
                         │
          ┌──────────────▼──────────────┐
          │   Frontend — React.js        │
          │  (GameLog Tracker Client)    │
          └──────┬───────────┬──────────┘
                 │           │
     ┌───────────▼───┐   ┌───▼──────────────────┐
     │  RAWG.io API  │   │  Backend — Laravel    │
     │  (Katalog     │   │  (GameLog Core API)   │
     │   Game)       │   │                       │
     └───────────────┘   │  ┌─────────────────┐  │
                         │  │  MySQL Database  │  │
                         │  └─────────────────┘  │
                         └──────────────────────┘
```

**Alur Request Backend:**
1. Frontend mengirim HTTP Request ke endpoint Backend dengan Bearer Token.
2. Middleware `auth:sanctum` memvalidasi token.
3. Controller memproses request dan berinteraksi dengan Model/Database via Eloquent.
4. Controller mengembalikan JSON Response yang terstandarisasi.

---

## 6. Skema Database

### 6.1 Tabel `users` (bawaan Laravel)

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `id` | BIGINT UNSIGNED (PK) | Auto-increment |
| `name` | VARCHAR | Nama lengkap pengguna |
| `email` | VARCHAR (UNIQUE) | Email unik pengguna |
| `password` | VARCHAR | Password ter-hash (bcrypt) |
| `email_verified_at` | TIMESTAMP, nullable | Waktu verifikasi email |
| `remember_token` | VARCHAR, nullable | Token remember me |
| `created_at` | TIMESTAMP | Waktu akun dibuat |
| `updated_at` | TIMESTAMP | Waktu akun diperbarui |

### 6.2 Tabel `game_logs` (Tabel Utama)

| Kolom | Tipe | Default | Keterangan |
|-------|------|---------|-----------|
| `id` | BIGINT UNSIGNED (PK) | — | Auto-increment |
| `user_id` | BIGINT UNSIGNED (FK) | — | Referensi ke `users.id`, CASCADE DELETE |
| `rawg_id` | BIGINT UNSIGNED | — | ID game dari RAWG.io |
| `title` | VARCHAR | — | Judul game |
| `status` | VARCHAR | `'playing'` | Enum: `wishlist`, `playing`, `completed` |
| `personal_rating` | INTEGER | `0` | Rating pribadi pengguna (0–5) |
| `created_at` | TIMESTAMP | — | Waktu entri dibuat |
| `updated_at` | TIMESTAMP | — | Waktu entri diperbarui |

### 6.3 Tabel `personal_access_tokens` (bawaan Sanctum)

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `id` | BIGINT UNSIGNED (PK) | Auto-increment |
| `tokenable_type` | VARCHAR | Polymorphic type |
| `tokenable_id` | BIGINT UNSIGNED | ID pemilik token |
| `name` | VARCHAR | Nama token |
| `token` | VARCHAR (UNIQUE) | Hash token |
| `abilities` | TEXT, nullable | Kemampuan/scope token |
| `last_used_at` | TIMESTAMP, nullable | Terakhir digunakan |
| `expires_at` | TIMESTAMP, nullable | Waktu kadaluarsa |
| `created_at` / `updated_at` | TIMESTAMP | — |

### Relasi Antar Tabel

```
users (1) ──────< (many) game_logs
  [id]                     [user_id]

users (1) ──────< (many) personal_access_tokens
  [id]                     [tokenable_id]
```

---

## 7. Struktur Direktori Backend (Laravel)

```
Backend_GameLog/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           ├── AuthController.php       # [PERLU DIBUAT] Register & Login
│   │           └── GameTrackerController.php # [PERLU DIBUAT] CRUD GameLog
│   ├── Models/
│   │   ├── User.php                         # ✅ Ada
│   │   └── GameLog.php                      # ✅ Ada
│   └── Providers/
├── database/
│   └── migrations/
│       ├── ..._create_users_table.php        # ✅ Ada
│       ├── ..._create_game_logs_table.php    # ✅ Ada
│       └── ..._create_personal_access_tokens_table.php # ✅ Ada
└── routes/
    └── api.php                               # ✅ Sudah terdefinisi
```

---

## 8. Spesifikasi Fitur Backend

### Fitur F1 — Registrasi Pengguna (Register)

**Deskripsi:** Membuat akun pengguna baru di sistem.

**Aturan Bisnis:**
- Email harus unik; jika sudah terdaftar, kembalikan error `422`.
- Password harus di-hash menggunakan `bcrypt` sebelum disimpan.
- Setelah registrasi berhasil, kembalikan token autentikasi secara langsung.

**Validasi Input:**
| Field | Rule |
|-------|------|
| `name` | required, string, max:255 |
| `email` | required, email, unique:users |
| `password` | required, string, min:8, confirmed |

---

### Fitur F2 — Login Pengguna

**Deskripsi:** Mengautentikasi pengguna yang sudah terdaftar dan mengembalikan Bearer Token.

**Aturan Bisnis:**
- Cocokkan email dan password dengan data di database.
- Jika kredensial salah, kembalikan error `401 Unauthorized`.
- Setiap login berhasil menghasilkan **token baru** via `createToken()` Sanctum.

**Validasi Input:**
| Field | Rule |
|-------|------|
| `email` | required, email |
| `password` | required |

---

### Fitur F3 — Tambah Game ke Tracker (Create)

**Deskripsi:** Menyimpan game pilihan pengguna ke dalam daftar tracker.

**Aturan Bisnis:**
- Endpoint hanya dapat diakses dengan Bearer Token yang valid (middleware `auth:sanctum`).
- `user_id` diambil otomatis dari token (tidak perlu dikirim oleh Frontend).
- Satu pengguna **tidak boleh menambahkan game yang sama dua kali** (cek duplikasi berdasarkan `user_id` + `rawg_id`). Jika duplikat, kembalikan error `409 Conflict`.
- `status` default adalah `'playing'` jika tidak disertakan.
- `personal_rating` default adalah `0` jika tidak disertakan.

**Validasi Input:**
| Field | Rule |
|-------|------|
| `rawg_id` | required, integer |
| `title` | required, string, max:255 |
| `status` | nullable, in:wishlist,playing,completed |
| `personal_rating` | nullable, integer, min:0, max:5 |

---

### Fitur F4 — Lihat Semua Game Tracker (Read)

**Deskripsi:** Mengambil semua entri game tracker milik pengguna yang sedang login.

**Aturan Bisnis:**
- Endpoint hanya dapat diakses dengan Bearer Token yang valid.
- Query database harus **difilter berdasarkan `user_id`** dari pengguna yang sedang login, bukan mengambil semua data di tabel.
- Mendukung filter opsional via query parameter `?status=playing` (wishlist / playing / completed).

---

### Fitur F5 — Perbarui Status / Rating Game (Update)

**Deskripsi:** Memperbarui kolom `status` dan/atau `personal_rating` dari entri tracker yang sudah ada.

**Aturan Bisnis:**
- Endpoint hanya dapat diakses dengan Bearer Token yang valid.
- Sistem harus **memverifikasi kepemilikan**: pastikan entri dengan `id` yang diminta memiliki `user_id` yang sesuai dengan pengguna yang sedang login. Jika tidak, kembalikan error `403 Forbidden` atau `404 Not Found`.
- Hanya field `status` dan `personal_rating` yang dapat diperbarui.

**Validasi Input:**
| Field | Rule |
|-------|------|
| `status` | nullable, in:wishlist,playing,completed |
| `personal_rating` | nullable, integer, min:0, max:5 |

---

### Fitur F6 — Hapus Game dari Tracker (Delete)

**Deskripsi:** Menghapus secara permanen entri game dari tracker pengguna.

**Aturan Bisnis:**
- Endpoint hanya dapat diakses dengan Bearer Token yang valid.
- Sistem harus **memverifikasi kepemilikan** sebelum menghapus. Pengguna hanya boleh menghapus data miliknya sendiri.
- Jika entri tidak ditemukan atau bukan milik pengguna, kembalikan error `404`.

---

## 9. Spesifikasi Endpoint API

> **Base URL:** `http://localhost:8000/api`  
> **Format Response:** `application/json`  
> **Autentikasi:** Laravel Sanctum (Bearer Token)

---

### 9.1 POST `/api/v1/register`

**Deskripsi:** Mendaftarkan akun pengguna baru.  
**Auth Required:** ❌ Tidak

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "Hafiz Rizky",
  "email": "hafiz@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response Sukses `201 Created`:**
```json
{
  "status": "success",
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "Hafiz Rizky",
      "email": "hafiz@example.com",
      "created_at": "2026-06-10T06:00:00Z"
    },
    "token": "1|abc123tokenstring..."
  }
}
```

**Response Gagal `422 Unprocessable Entity`:**
```json
{
  "status": "error",
  "message": "The email has already been taken.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

---

### 9.2 POST `/api/v1/login`

**Deskripsi:** Login pengguna dan mendapatkan Bearer Token.  
**Auth Required:** ❌ Tidak

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "email": "hafiz@example.com",
  "password": "password123"
}
```

**Response Sukses `200 OK`:**
```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "Hafiz Rizky",
      "email": "hafiz@example.com"
    },
    "token": "2|xyz789tokenstring..."
  }
}
```

**Response Gagal `401 Unauthorized`:**
```json
{
  "status": "error",
  "message": "Invalid credentials. Email or password is wrong."
}
```

---

### 9.3 POST `/api/v1/gamelogs`

**Deskripsi:** Menambahkan game ke tracker pribadi pengguna.  
**Auth Required:** ✅ Ya — `Bearer <token>`

**Request Headers:**
```
Authorization: Bearer <token>
Content-Type: application/json
```

**Request Body:**
```json
{
  "rawg_id": 41494,
  "title": "Cyberpunk 2077",
  "status": "playing",
  "personal_rating": 0
}
```

**Response Sukses `201 Created`:**
```json
{
  "status": "success",
  "message": "Game successfully added to tracker",
  "data": {
    "id": 1,
    "rawg_id": 41494,
    "title": "Cyberpunk 2077",
    "status": "playing",
    "personal_rating": 0,
    "created_at": "2026-06-10T13:00:00Z"
  }
}
```

**Response Gagal `409 Conflict` (Duplikat):**
```json
{
  "status": "error",
  "message": "Game already exists in your tracker"
}
```

**Response Gagal `401 Unauthorized` (Token tidak ada/salah):**
```json
{
  "status": "error",
  "message": "Unauthorized or Invalid Token"
}
```

---

### 9.4 GET `/api/v1/gamelogs`

**Deskripsi:** Mengambil semua daftar game tracker milik pengguna yang login.  
**Auth Required:** ✅ Ya — `Bearer <token>`  
**Query Params Opsional:** `?status=playing` | `?status=wishlist` | `?status=completed`

**Request Headers:**
```
Authorization: Bearer <token>
Content-Type: application/json
```

**Response Sukses `200 OK`:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "rawg_id": 41494,
      "title": "Cyberpunk 2077",
      "status": "playing",
      "personal_rating": 0
    },
    {
      "id": 2,
      "rawg_id": 3328,
      "title": "The Witcher 3: Wild Hunt",
      "status": "completed",
      "personal_rating": 5
    }
  ]
}
```

**Response Sukses (Data Kosong) `200 OK`:**
```json
{
  "status": "success",
  "data": []
}
```

**Response Gagal `401 Unauthorized`:**
```json
{
  "status": "error",
  "message": "Unauthorized or Invalid Token"
}
```

---

### 9.5 PUT `/api/v1/gamelogs/{id}`

**Deskripsi:** Memperbarui status dan/atau rating personal game tracker.  
**Auth Required:** ✅ Ya — `Bearer <token>`  
**URL Param:** `{id}` = ID entri di tabel `game_logs`

**Request Headers:**
```
Authorization: Bearer <token>
Content-Type: application/json
```

**Request Body:**
```json
{
  "status": "completed",
  "personal_rating": 5
}
```

**Response Sukses `200 OK`:**
```json
{
  "status": "success",
  "message": "GameLog updated successfully",
  "data": {
    "id": 1,
    "title": "Cyberpunk 2077",
    "status": "completed",
    "personal_rating": 5,
    "updated_at": "2026-06-10T14:00:00Z"
  }
}
```

**Response Gagal `404 Not Found`:**
```json
{
  "status": "error",
  "message": "GameLog entry not found"
}
```

**Response Gagal `403 Forbidden` (Bukan milik pengguna):**
```json
{
  "status": "error",
  "message": "Unauthorized action. This log does not belong to you."
}
```

---

### 9.6 DELETE `/api/v1/gamelogs/{id}`

**Deskripsi:** Menghapus game secara permanen dari tracker pengguna.  
**Auth Required:** ✅ Ya — `Bearer <token>`  
**URL Param:** `{id}` = ID entri di tabel `game_logs`

**Request Headers:**
```
Authorization: Bearer <token>
Content-Type: application/json
```

**Response Sukses `200 OK`:**
```json
{
  "status": "success",
  "message": "Game successfully removed from tracker"
}
```

**Response Gagal `404 Not Found`:**
```json
{
  "status": "error",
  "message": "GameLog entry not found or unauthorized action"
}
```

---

## 10. Ringkasan Semua Endpoint

| # | Method | Endpoint | Auth | Deskripsi |
|---|--------|----------|------|-----------|
| 1 | `POST` | `/api/v1/register` | ❌ | Registrasi pengguna baru |
| 2 | `POST` | `/api/v1/login` | ❌ | Login & dapatkan token |
| 3 | `POST` | `/api/v1/gamelogs` | ✅ | Tambah game ke tracker |
| 4 | `GET` | `/api/v1/gamelogs` | ✅ | Ambil semua game tracker |
| 5 | `PUT` | `/api/v1/gamelogs/{id}` | ✅ | Update status/rating game |
| 6 | `DELETE` | `/api/v1/gamelogs/{id}` | ✅ | Hapus game dari tracker |

---

## 11. Aturan Bisnis & Keamanan

| Aturan | Implementasi |
|--------|-------------|
| **Isolasi Data Per User** | Setiap query ke `game_logs` harus menggunakan `->where('user_id', auth()->id())`. |
| **Validasi Kepemilikan (Authorization)** | Sebelum UPDATE/DELETE, pastikan `game_log->user_id === auth()->id()`. |
| **Hash Password** | Gunakan `Hash::make($password)` — tidak boleh menyimpan password plain-text. |
| **Token Sanctum** | Setiap token dibuat dengan `$user->createToken('api-token')->plainTextToken`. |
| **Validasi Duplikasi** | Sebelum INSERT, cek apakah `rawg_id` + `user_id` sudah ada di database. |
| **CORS** | Konfigurasi CORS di `config/cors.php` untuk mengizinkan request dari domain Frontend. |

---

## 12. Penanganan Error Global

| HTTP Status | Skenario |
|-------------|----------|
| `200 OK` | Request berhasil (GET, PUT, DELETE) |
| `201 Created` | Data baru berhasil dibuat (POST) |
| `401 Unauthorized` | Token tidak ada, tidak valid, atau salah kredensial login |
| `403 Forbidden` | Token valid tapi tidak punya hak akses ke resource tersebut |
| `404 Not Found` | Entri yang dicari tidak ada di database |
| `409 Conflict` | Data duplikat (game sudah ada di tracker pengguna) |
| `422 Unprocessable Entity` | Validasi input gagal |
| `500 Internal Server Error` | Kesalahan server yang tidak terduga |

---

## 13. File yang Harus Diimplementasikan

> [!IMPORTANT]
> Berdasarkan pemeriksaan kode saat ini, direktori `app/Http/Controllers/Api/` masih **kosong**. File-file berikut wajib dibuat agar backend berfungsi penuh.

| File | Status | Keterangan |
|------|--------|-----------|
| `app/Http/Controllers/Api/AuthController.php` | 🔴 Belum ada | Logic Register & Login |
| `app/Http/Controllers/Api/GameTrackerController.php` | 🔴 Belum ada | Logic CRUD GameLog |
| `app/Models/GameLog.php` | ✅ Ada | Model Eloquent GameLog |
| `app/Models/User.php` | ✅ Ada | Model Eloquent User |
| `routes/api.php` | ✅ Sudah terdefinisi | Routing sudah benar |
| `database/migrations/..._create_game_logs_table.php` | ✅ Ada | Schema database sudah benar |

---

## 14. Rencana Pengujian (Testing Plan)

### Manual Testing dengan Postman / Insomnia

| Test Case | Method | Endpoint | Expected Result |
|-----------|--------|----------|-----------------|
| Register akun baru | POST | `/api/v1/register` | `201` + token |
| Register email duplikat | POST | `/api/v1/register` | `422` error |
| Login berhasil | POST | `/api/v1/login` | `200` + token |
| Login salah password | POST | `/api/v1/login` | `401` error |
| Tambah game (dengan token) | POST | `/api/v1/gamelogs` | `201` + data |
| Tambah game tanpa token | POST | `/api/v1/gamelogs` | `401` error |
| Tambah game duplikat | POST | `/api/v1/gamelogs` | `409` error |
| Lihat semua game tracker | GET | `/api/v1/gamelogs` | `200` + array data |
| Update status game | PUT | `/api/v1/gamelogs/{id}` | `200` + data updated |
| Update game orang lain | PUT | `/api/v1/gamelogs/{id}` | `403/404` error |
| Hapus game dari tracker | DELETE | `/api/v1/gamelogs/{id}` | `200` success |
| Hapus game tidak ada | DELETE | `/api/v1/gamelogs/{id}` | `404` error |

### Automated Testing (PHPUnit)

```bash
php artisan test
```

> File test tersedia di direktori `tests/Feature/` dan `tests/Unit/`.

---

## 15. Cara Menjalankan Proyek Backend

```bash
# 1. Masuk ke direktori backend
cd backend

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di file .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=gamelog_db
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migrasi
php artisan migrate

# 7. Jalankan server lokal
php artisan serve
# Server berjalan di: http://localhost:8000
```

---

*Dokumen ini disusun berdasarkan:*  
- `Intruksi-Tugas/01-identitas-kelompok.md`  
- `Intruksi-Tugas/02-rencana-fitur.md`  
- `Intruksi-Tugas/03-api-spec.md`  
- Pemeriksaan langsung kode sumber di direktori `backend/`
