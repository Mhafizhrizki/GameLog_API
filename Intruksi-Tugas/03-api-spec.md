# API Specification

> Dokumentasikan setiap endpoint yang dikembangkan maupun yang dikonsumsi dari layanan eksternal.
> Salin dan ulangi blok di bawah untuk setiap endpoint tambahan.

---

## [Search Games (RAWG API)]

**Method:** `[GET]`

**URL:** `https://api.rawg.io/api/games`

**Deskripsi:** `[Mengambil katalog game atau mencari game tertentu berdasarkan query parameter (search, genres, platforms) langsung dari API RAWG.io.]`

**Autentikasi Diperlukan:** `[Ya]`

**Sumber:** `[Third-Party API — RAWG.io]`

**Request Headers:**

```
Authorization: Bearer <token>
Content-Type: application/json
```

**Request Body:**

**Response Sukses (`200 OK`):**

```json
{
  "status": "success",
  "data": {
    "results": [
      {
        "rawg_id": 41494,
        "name": "Cyberpunk 2077",
        "released": "2020-12-10",
        "background_image": "https://media.rawg.io/media/games/...",
        "rating": 4.1
      }
    ]
  }
}
```

**Response Gagal:**

```json
{
  "status": "error",
  "message": "Failed to fetch data from RAWG.io"
}
```

---

## [Create — Add Game to Tracker]

**Method:** `[POST]`

**URL:** `/api/v1/gamelogs`

**Deskripsi:** `[Menambahkan game dari hasil pencarian (RAWG) ke dalam daftar tracker milik pengguna.]`

**Autentikasi Diperlukan:** `[Ya]`

**Sumber:** `[Internal System]`

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

**Response Sukses (`201 Created`):**

```json
{
  "status": "success",
  "message": "Game successfully added to tracker",
  "data": {
    "id": "log_abc123",
    "rawg_id": 41494,
    "title": "Cyberpunk 2077",
    "status": "playing",
    "personal_rating": 0,
    "created_at": "2026-06-04T13:33:31Z"
  }
}
```

**Response Gagal:**

```json
{
  "status": "error",
  "message": "Game already exists in your tracker"
}
```

---

## [Read — Get User's GameLogs]

**Method:** `[GET]`

**URL:** `/api/v1/gamelogs`

**Deskripsi:** `Mengambil semua daftar game yang ada di dalam tracker milik pengguna yang sedang login.`

**Autentikasi Diperlukan:** `[Ya]`

**Sumber:** `[Internal System]`

**Request Headers:**

```
Authorization: Bearer <token>
Content-Type: application/json
```

**Request Body:**

**Response Sukses (`200 OK`):**

```json
{
  "status": "success",
  "data": [
    {
      "id": "log_abc123",
      "rawg_id": 41494,
      "title": "Cyberpunk 2077",
      "status": "playing",
      "personal_rating": 0
    },
    {
      "id": "log_xyz789",
      "rawg_id": 3328,
      "title": "The Witcher 3: Wild Hunt",
      "status": "completed",
      "personal_rating": 5
    }
  ]
}
```

**Response Gagal:**

```json
{
  "status": "error",
  "message": "Unauthorized or Invalid Token"
}
```

---

## [Update — Update Game Status/Rating]

**Method:** `[PUT]`

**URL:** `/api/v1/gamelogs/:id`

**Deskripsi:** `Memperbarui progress, status (misal dari playing menjadi completed), atau personal rating dari game yang sudah ada di tracker.`

**Autentikasi Diperlukan:** `[Ya]`

**Sumber:** `[Internal System]`
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

**Response Sukses (`200 OK`):**

```json
{
  "status": "success",
  "message": "GameLog updated successfully",
  "data": {
    "id": "log_abc123",
    "title": "Cyberpunk 2077",
    "status": "completed",
    "personal_rating": 5,
    "updated_at": "2026-06-04T14:00:00Z"
  }
}
```

**Response Gagal:**

```json
{
  "status": "error",
  "message": "GameLog entry not found"
}
```

---

## [Delete — Remove Game from Tracker]

**Method:** `[DELETE]`

**URL:** `/api/v1/gamelogs/:id`

**Deskripsi:** `Menghapus game secara permanen dari daftar tracker pengguna.`

**Autentikasi Diperlukan:** `[Ya]`

**Sumber:** `[Internal System]`

**Request Headers:**

```
Authorization: Bearer <token>
Content-Type: application/json
```

**Request Body:**

**Response Sukses (`200 OK`):**

```json
{
  "status": "success",
  "message": "Game successfully removed from tracker"
}
```

**Response Gagal:**

```json
{
  "status": "error",
  "message": "GameLog entry not found or unauthorized action"
}
```

_(Salin blok template di atas untuk setiap endpoint selanjutnya)_
