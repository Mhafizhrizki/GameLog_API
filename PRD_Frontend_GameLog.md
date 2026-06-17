# 📄 PRD Frontend — GameLog Tracker Client (React.js)

> **Kelompok 10** | Mata Kuliah: Pemrograman Berbasis Web / Framework
> **Repositori:** https://github.com/Mhafizhrizki/GameLog_API
> **Tanggal:** 16 Juni 2026
> **Versi Dokumen:** 1.0.0

---

## 1. Latar Belakang & Gambaran Proyek

**GameLog Tracker Client** adalah aplikasi web berbasis React.js yang berfungsi sebagai antarmuka pengguna (UI) untuk sistem GameLog Tracker. Frontend ini bertugas menampilkan katalog game dari **RAWG.io API** dan mengelola daftar game pribadi pengguna melalui **Backend Laravel**.

Sistem menggunakan arsitektur **Decoupled (terpisah)**:
- **Frontend** (React.js) → mengonsumsi RAWG.io API untuk katalog game & memanggil Backend Laravel untuk operasi CRUD data pengguna.
- **Backend** (Laravel) → menyediakan RESTful API untuk autentikasi dan penyimpanan data.

---

## 2. Tujuan Produk Frontend

| # | Tujuan |
|---|--------|
| 1 | Menyediakan antarmuka **Register & Login** yang aman menggunakan token dari Backend Laravel. |
| 2 | Menampilkan **katalog game** dari RAWG.io dengan fitur pencarian real-time. |
| 3 | Menyediakan **Dashboard pribadi** untuk mengelola game tracker (Wishlist, Playing, Completed). |
| 4 | Memungkinkan pengguna **menambah, mengubah status, dan menghapus** game dari tracker. |
| 5 | Menampilkan **statistik ringkasan** aktivitas game pengguna. |
| 6 | Memberikan pengalaman pengguna (UX) yang **responsif dan modern** di semua ukuran layar. |

---

## 3. Tim & Tanggung Jawab

| Peran | Anggota | NIM | Teknologi |
|-------|---------|-----|-----------|
| **Frontend & Dev** | Achmad Faris Faqih | 230705001 | React.js |
| Backend & Dev | M. Hafiz Rizky | 230705212 | Laravel |

---

## 4. Stack Teknologi Frontend

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| **Bahasa** | JavaScript (JSX) | ES2022+ |
| **Framework** | React.js | ^18.x |
| **Build Tool** | Vite | ^5.x |
| **Routing** | React Router DOM | ^6.x |
| **HTTP Client** | Axios | ^1.x |
| **State Management** | React Context API + useState/useEffect | — |
| **Styling** | CSS Modules / Vanilla CSS | — |
| **External API** | RAWG.io REST API | v1 |
| **Backend API** | GameLog Tracker Core API (Laravel) | — |

---

## 5. Arsitektur Sistem Frontend

```
┌────────────────────────────────────────────────────────────┐
│                  BROWSER (React.js SPA)                     │
│                                                              │
│  ┌──────────┐  ┌──────────────┐  ┌──────────────────────┐  │
│  │Auth Pages│  │Discover Page │  │   Dashboard Pages    │  │
│  │Login /   │  │Search Game   │  │Library / Statistics  │  │
│  │Register  │  │              │  │                      │  │
│  └────┬─────┘  └──────┬───────┘  └──────────┬───────────┘  │
│       │               │                      │              │
│  ┌────▼───────────────▼──────────────────────▼───────────┐  │
│  │             API Service Layer (Axios)                   │  │
│  └──────────────┬──────────────────────────┬─────────────┘  │
└─────────────────┼──────────────────────────┼────────────────┘
                  │                          │
     ┌────────────▼────────┐    ┌────────────▼──────────────┐
     │  RAWG.io REST API   │    │ Backend Laravel            │
     │  (Katalog Game)     │    │ http://localhost:8000/api  │
     └─────────────────────┘    └───────────────────────────┘
```

**Alur State Autentikasi:**
1. User login → Backend mengembalikan Bearer Token.
2. Token disimpan di `localStorage`.
3. Setiap request ke Backend menyertakan header `Authorization: Bearer <token>`.
4. Jika token tidak valid → redirect otomatis ke halaman Login.

---

## 6. Struktur Halaman (Pages)

| # | Halaman | Route | Auth | Deskripsi |
|---|---------|-------|------|-----------|
| 1 | **Landing / Home** | `/` | ❌ | Pengenalan aplikasi dengan CTA Login/Register |
| 2 | **Register** | `/register` | ❌ | Form pendaftaran akun baru |
| 3 | **Login** | `/login` | ❌ | Form login pengguna |
| 4 | **Discover** | `/discover` | ✅ | Cari & jelajahi game dari RAWG.io |
| 5 | **My Library** | `/library` | ✅ | Dashboard daftar game tracker pengguna |
| 6 | **Statistics** | `/statistics` | ✅ | Ringkasan statistik aktivitas game |

> Halaman ✅ dilindungi **Protected Route** — jika belum login, redirect ke `/login`.

---

## 7. Spesifikasi Fitur Frontend

### F1 — Register

**Endpoint:** `POST /api/v1/register`

**Komponen UI:**
- Input: `name`, `email`, `password`, `password_confirmation`
- Tombol "Daftar" + link ke Login

**Aturan:**
- Validasi form di sisi client sebelum dikirim ke API.
- Tampilkan pesan error dari API jika email duplikat (`422`).
- Berhasil → simpan token → redirect ke `/discover`.

---

### F2 — Login

**Endpoint:** `POST /api/v1/login`

**Komponen UI:**
- Input: `email`, `password`
- Tombol "Masuk" + link ke Register

**Aturan:**
- Tampilkan error jika kredensial salah (`401`).
- Berhasil → simpan token ke `localStorage` → redirect ke `/discover`.

---

### F3 — Logout

**Endpoint:** `POST /api/v1/logout`

**Aturan:**
- Tombol Logout ada di Navbar.
- Saat diklik → panggil API → hapus token dari `localStorage` → redirect ke `/login`.

---

### F4 — Discover & Search Games (RAWG.io)

**Endpoint RAWG.io:**
```
GET https://api.rawg.io/api/games?key={KEY}&search={query}&page_size=20
```
**Endpoint Backend:** `POST /api/v1/gamelogs`

**Komponen UI:**
- Search bar dengan debounce 300ms
- Grid kartu game (cover, nama, rating)
- Tombol "Tambah ke Tracker" per kartu
- Modal pilih status (Wishlist / Playing / Completed)

**Aturan:**
- Default tampilkan game populer.
- Jika game sudah di tracker → badge "Sudah di Tracker" + tombol nonaktif.

---

### F5 — My Library Dashboard

**Endpoint:** `GET /api/v1/gamelogs?status={status}`

**Komponen UI:**
- Tab filter: `Semua | Playing | Wishlist | Completed`
- Kartu: cover, judul, status badge, rating bintang
- Tombol "Edit" dan "Hapus"

**Aturan:**
- Data diambil saat halaman pertama dibuka.
- Filter via query param ke Backend.
- Jika kosong → empty state + CTA ke `/discover`.

---

### F6 — Update Status / Rating

**Endpoint:** `PUT /api/v1/gamelogs/{id}`

**Komponen UI:**
- Modal edit: dropdown status + input rating bintang (1–5)

**Aturan:**
- Hanya field yang berubah yang dikirim.
- Berhasil → refresh library tanpa reload penuh.

---

### F7 — Hapus Game dari Tracker

**Endpoint:** `DELETE /api/v1/gamelogs/{id}`

**Komponen UI:**
- Tombol "Hapus" + dialog konfirmasi

**Aturan:**
- Setelah konfirmasi → kirim DELETE → kartu hilang dari UI.

---

### F8 — Statistics Dashboard

**Endpoint:** `GET /api/v1/user/statistics`

**Komponen UI:**
- Kartu statistik: Total Game, Completed, Playing, Wishlist, Rata-rata Rating.

---

## 8. Struktur Direktori Frontend

```
frontend/
├── public/
│   └── index.html
├── src/
│   ├── api/                          # Axios instance & API calls
│   │   ├── axiosInstance.js          # Base URL + interceptor token otomatis
│   │   ├── authApi.js                # register, login, logout
│   │   ├── gameLogApi.js             # CRUD gamelogs
│   │   ├── statisticsApi.js          # getStatistics
│   │   └── rawgApi.js                # searchGames dari RAWG.io
│   │
│   ├── context/                      # State Management global
│   │   └── AuthContext.jsx           # user, token, login(), logout()
│   │
│   ├── components/                   # Reusable UI Components
│   │   ├── Navbar.jsx                # Navigasi + Logout
│   │   ├── ProtectedRoute.jsx        # Guard halaman auth
│   │   ├── GameCard.jsx              # Kartu game (Discover)
│   │   ├── TrackerCard.jsx           # Kartu game (Library)
│   │   ├── EditModal.jsx             # Modal edit status/rating
│   │   ├── AddToTrackerModal.jsx     # Modal pilih status saat tambah
│   │   ├── StatCard.jsx              # Kartu statistik
│   │   └── LoadingSpinner.jsx        # Indikator loading
│   │
│   ├── pages/                        # Halaman utama
│   │   ├── LandingPage.jsx           # Route: /
│   │   ├── LoginPage.jsx             # Route: /login
│   │   ├── RegisterPage.jsx          # Route: /register
│   │   ├── DiscoverPage.jsx          # Route: /discover
│   │   ├── LibraryPage.jsx           # Route: /library
│   │   └── StatisticsPage.jsx        # Route: /statistics
│   │
│   ├── App.jsx                       # Router utama
│   ├── main.jsx                      # Entry point
│   └── index.css                     # Global styles
│
├── .env                              # VITE_RAWG_API_KEY, VITE_API_BASE_URL
├── .env.example
├── package.json
└── vite.config.js
```

---

## 9. Konfigurasi Environment

```env
# frontend/.env
VITE_API_BASE_URL=http://localhost:8000/api
VITE_RAWG_API_KEY=your_rawg_api_key_here
```

> Daftar RAWG API Key gratis di: https://rawg.io/apidocs

---

## 10. Integrasi API — Contoh Implementasi

### Axios Instance dengan Interceptor Token

```javascript
// src/api/axiosInstance.js
import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: { 'Content-Type': 'application/json' },
});

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
```

### RAWG.io Search

```javascript
// src/api/rawgApi.js
const RAWG_BASE = 'https://api.rawg.io/api';
const RAWG_KEY  = import.meta.env.VITE_RAWG_API_KEY;

export const searchGames = async (query = '', page = 1) => {
  const params = new URLSearchParams({
    key: RAWG_KEY, search: query, page_size: 20, page,
  });
  const res = await fetch(`${RAWG_BASE}/games?${params}`);
  return res.json();
};
```

---

## 11. Aturan UI/UX & Desain

| Aspek | Ketentuan |
|-------|-----------|
| **Responsif** | Mendukung mobile (≥320px), tablet, desktop |
| **Color Palette** | Dark theme: `#0f172a` (bg), `#6366f1` (primary), `#22d3ee` (accent) |
| **Typography** | Google Fonts: `Inter` atau `Outfit` |
| **Loading State** | Skeleton loader atau spinner untuk setiap request API |
| **Error State** | Toast notification atau pesan inline untuk error API |
| **Empty State** | Ilustrasi + CTA jika data kosong |
| **Animasi** | Transisi halaman halus, hover effect pada kartu game |

---

## 12. Ringkasan Endpoint yang Dikonsumsi

### Backend Laravel (`http://localhost:8000/api`)

| Method | Endpoint | Fitur |
|--------|----------|-------|
| `POST` | `/v1/register` | Register |
| `POST` | `/v1/login` | Login |
| `POST` | `/v1/logout` | Logout |
| `GET` | `/v1/gamelogs` | Library |
| `POST` | `/v1/gamelogs` | Tambah ke Tracker |
| `PUT` | `/v1/gamelogs/{id}` | Update Status/Rating |
| `DELETE` | `/v1/gamelogs/{id}` | Hapus Game |
| `GET` | `/v1/user/statistics` | Statistics |

### RAWG.io (`https://api.rawg.io/api`)

| Method | Endpoint | Fitur |
|--------|----------|-------|
| `GET` | `/games?key=&search=` | Discover & Search |

---

## 13. Rencana Pengujian Frontend

| Test Case | Expected Result |
|-----------|-----------------|
| Register akun baru | Redirect ke `/discover` + token tersimpan |
| Register email duplikat | Tampil pesan error 422 |
| Login berhasil | Redirect ke `/discover` |
| Login salah password | Tampil pesan error 401 |
| Akses `/library` tanpa login | Redirect ke `/login` |
| Cari game di Discover | Daftar game dari RAWG.io tampil |
| Tambah game ke tracker | Kartu muncul di Library |
| Tambah game duplikat | Badge "Sudah di Tracker" tampil |
| Update status game | Status berubah di Library |
| Hapus game | Kartu hilang dari Library |
| Logout | Token terhapus, redirect `/login` |

---

## 14. Cara Menjalankan Project Frontend

```bash
# 1. Masuk ke direktori frontend
cd frontend

# 2. Install dependencies
npm install

# 3. Buat file environment
cp .env.example .env
# Edit .env → isi VITE_RAWG_API_KEY dan VITE_API_BASE_URL

# 4. Jalankan development server
npm run dev
# Berjalan di: http://localhost:5173

# 5. Pastikan Backend Laravel berjalan di:
# http://localhost:8000  (php artisan serve)
```

---

## 15. Dependensi Antar Tim

| Kebutuhan Frontend | Dipenuhi Oleh | Status |
|--------------------|---------------|--------|
| Endpoint Register & Login | Backend (Hafizh) | ✅ Sudah tersedia |
| Endpoint CRUD GameLogs | Backend (Hafizh) | ✅ Sudah tersedia |
| Endpoint Statistics | Backend (Hafizh) | ✅ Sudah tersedia |
| RAWG.io API Key | Frontend (Faris) | ⚠️ Perlu daftar di rawg.io |
| CORS aktif di Backend | Backend (Hafizh) | ⚠️ Perlu dicek `config/cors.php` |

---

*Dokumen ini disusun berdasarkan:*
- `Intruksi-Tugas/01-identitas-kelompok.md`
- `Intruksi-Tugas/02-rencana-fitur.md`
- `Intruksi-Tugas/03-api-spec.md`
- `PRD_Backend_GameLog.md`
- Analisis kode sumber Backend di direktori `backend/`
