# Identitas Kelompok

---

**Nama Kelompok:** `KELOMPOK 10`

**Nama Proyek / Aplikasi:** `GameLog Tracker API`

**Jumlah Anggota:** `2` orang

**Repositori:** `https://github.com/Mhafizhrizki/GameLog_API`

---

## Anggota & Role

**Anggota 1**

- Nama Lengkap: `M. Hafiz Rizky`
- NIM: `2307050212`
- Role: `Frontend & Dev`
- Teknologi: `React`

**Anggota 2**

- Nama Lengkap: `Achmad Faris Faqih`
- NIM: `230705001`
- Role: `Backend & Dev`
- Teknologi: `Laravel`

## Stack Teknologi

**Frontend:** `React`
_(Bebas, contoh: React, Vue, Next.js, Nuxt, Svelte)_

**Backend:** `Laravel` _(wajib)_
_(Versi dan pilihan database driver menyesuaikan kebutuhan kelompok)_

**Database:** `MySQL`
_(Contoh: MySQL, PostgreSQL, SQLite)_

**DevOps / Infrastruktur:** `Github Action`
_(Contoh: Docker, GitHub Actions, Nginx, Railway, VPS)_

---

## Arsitektur Aplikasi

Proyek ini menggunakan arsitektur Decoupled (terpisah) di mana React.js bertindak sebagai Frontend yang berinteraksi langsung dengan pengguna untuk menampilkan antarmuka dan mengambil katalog game dari RAWG.io API. Ketika pengguna melakukan aksi CRUD (seperti menambah atau memperbarui daftar pelacakan game), Frontend akan mengirimkan request ke Laravel sebagai Backend. Laravel kemudian mengolah logika bisnis tersebut dan menyimpannya ke dalam database MySQL untuk mengamankan data pengguna serta riwayat game tracker mereka secara permanen.

**Aplikasi 1 — Frontend**

- Nama Aplikasi: `React.js - GameLog Tracker Client`
- Deskripsi Singkat: `Antarmuka web berbasis React untuk mencari game, mengelola log tracker pribadi (Wishlist, Playing, Completed), dan melihat review`
- Berkomunikasi dengan: `GameLog Tracker Core API (Backend Laravel) untuk data user & log CRUD, serta RAWG.io API untuk data katalog game.`

**Aplikasi 2 — Backend (Laravel)**

- Nama Aplikasi / Service: `GameLog Tracker Core API`
- Deskripsi Singkat: `RESTful API berbasis Laravel yang mengelola autentikasi user, menyediakan endpoint operasi CRUD tracker game, dan menyimpan data ke database.`
- Menyediakan layanan untuk: `ameLog Tracker Client (Frontend React).`
