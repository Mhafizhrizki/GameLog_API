# Rencana Fitur

> Dokumentasikan minimal **5 fitur utama** proyek Anda.
> Salin dan ulangi blok di bawah untuk setiap fitur tambahan.

---

## Fitur 1 — [Discover & Search Games]

**Role Penanggung Jawab:** `[Frontend]`

**Sumber Data:** `[Third-Party API — RAWG.io]`

**Deskripsi & Ekspektasi:**
`[Fitur ini memungkinkan pengguna untuk menjelajahi katalog game populer, rilisan terbaru, serta mencari game berdasarkan judul, genre, atau platform secara real-time. Ekspektasinya, Frontend dapat langsung menembak API RAWG.io dan menampilkan hasilnya dalam bentuk kartu-kartu game yang menarik secara instan.]`

---

## Fitur 2 — [Add to Game Tracker (Create)]

**Role Penanggung Jawab:** `[Backend]`

**Sumber Data:** `[Internal System & Third-Party API — RAWG.io]`

**Deskripsi & Ekspektasi:**
`[Pengguna dapat menambahkan game yang mereka temukan dari katalog RAWG.io ke dalam daftar pelacakan (tracker) pribadi mereka. Ekspektasinya, Backend Laravel menyediakan endpoint POST untuk menyimpan ID game, nama game, gambar game, beserta status awal (misal: *Wishlist* atau *Playing*) ke dalam database MySQL.]`

---

## Fitur 3 — [My Game Library Dashboard (Read)]

**Role Penanggung Jawab:** `[Frontend]`

**Sumber Data:** `[Internal System]`

**Deskripsi & Ekspektasi:**
`[Halaman dashboard khusus untuk menampilkan semua daftar game yang sedang atau telah dilacak oleh pengguna. Ekspektasinya, Frontend akan mengambil data dari Backend Laravel melalui endpoint GET, lalu menampilkannya dalam tab kategori yang rapi (seperti koleksi game yang *Sedang Dimainkan*, *Ingin Dimainkan*, atau *Selesai*).]`

---

## Fitur 4 — [Update Gameplay Status & Progress (Update)]

**Role Penanggung Jawab:** `[Backend]`

**Sumber Data:** `[Internal System]`

**Deskripsi & Ekspektasi:**
`[Fitur untuk memperbarui status atau catatan dari game yang sedang dilacak. Misalnya, mengubah status game dari *Playing* menjadi *Completed*, atau menambahkan rating pribadi. Ekspektasinya, Backend menyediakan endpoint PUT/PATCH untuk memperbarui baris data spesifik di database berdasarkan ID tracker yang dikirim oleh Frontend.]`

---

## Fitur 5 — [Remove Game from Tracker (Delete)]

**Role Penanggung Jawab:** `[Frontend]`

**Sumber Data:** `[Internal System]`

**Deskripsi & Ekspektasi:**
`[Fitur yang memungkinkan pengguna untuk menghapus game dari daftar pustaka pribadi mereka jika terjadi salah input atau tidak ingin melacak game itu lagi. Ekspektasinya, Backend menyediakan endpoint DELETE untuk menghapus data tracker terkait dari database MySQL, dan Frontend akan langsung memperbarui tampilan dashboard setelah penghapusan berhasil.]`

---

_(Salin blok di atas untuk fitur selanjutnya)_
