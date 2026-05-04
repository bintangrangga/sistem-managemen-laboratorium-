# DOKUMENTASI API  
## Sistem Manajemen Kegiatan Laboratorium

---

## 1. Pendahuluan

Dokumentasi ini berisi daftar endpoint API yang digunakan dalam Sistem Manajemen Kegiatan Laboratorium. API ini berfungsi sebagai penghubung antara backend dengan frontend website maupun mobile.

---

## 2. Base URL

Local Domain:

```text
http://kegiatan_lab.test/api/
```

Alternatif:

```text
http://localhost/kegiatan_lab/api/
```

---

## 3. Format Response

### Success

```json
{
  "status": "success",
  "message": "...",
  "data": []
}
```

### Error

```json
{
  "status": "error",
  "message": "..."
}
```

---

## 4. Authentication / Login

### Endpoint

```http
POST /login.php
```

### Request Body

| Parameter | Type | Keterangan |
|----------|------|------------|
| email | string | Email user |
| password | string | Password user |

### Response

```json
{
  "status": "success",
  "message": "Login berhasil",
  "data": {
    "id": 1,
    "nama": "Bintang Rangga Saputra",
    "role": "super_admin"
  }
}
```

---

## 5. API Users

### Endpoint

```http
GET /users.php
POST /users.php
PUT /users.php
DELETE /users.php
```

### Field

| Field | Type |
|------|------|
| nama | string |
| email | string |
| password | string |
| nim | string |
| role | enum |
| prodi | string |
| tempat_lahir | string |
| tanggal_lahir | date |

### Role

- super_admin
- admin
- user

---

## 6. API Kegiatan

### Endpoint

```http
GET /kegiatan.php
POST /kegiatan.php
PUT /kegiatan.php
DELETE /kegiatan.php
```

### Field

| Field | Type |
|------|------|
| nama_kegiatan | string |
| tanggal | date |
| status | enum |

### Status

- aktif
- nonaktif

---

## 7. API Absensi

### Endpoint

```http
GET /absensi.php
POST /absensi.php
PUT /absensi.php
DELETE /absensi.php
```

### Field

| Field | Type |
|------|------|
| user_id | int |
| kegiatan_id | int |
| status_kehadiran | enum |

### Status Kehadiran

- hadir
- izin
- sakit
- alfa

### Response

```json
{
  "status": "success",
  "data": [
    {
      "id": "1",
      "nama_user": "Bintang Rangga Saputra",
      "nama_kegiatan": "Seminar AI",
      "status_kehadiran": "hadir"
    }
  ]
}
```

---

## 8. API Inventaris

### Endpoint

```http
GET /inventaris.php
POST /inventaris.php
PUT /inventaris.php
DELETE /inventaris.php
```

### Field

| Field | Type |
|------|------|
| nama_barang | string |
| stok | int |
| kondisi | enum |
| status | enum |

### Kondisi

- baik
- rusak
- maintenance

### Status

- tersedia
- dipinjam
- maintenance
- habis

---

## 9. API Peminjaman

### Endpoint

```http
GET /peminjaman.php
POST /peminjaman.php
PUT /peminjaman.php
```

### Request Body

| Field | Type |
|------|------|
| user_id | int |
| kegiatan_id | int |
| inventaris_id | int |
| jumlah | int |

### Status

- dipinjam
- dikembalikan

### Logic

#### Saat meminjam:
- cek stok
- insert transaksi
- kurangi stok inventaris

#### Saat mengembalikan:
- update status
- isi tanggal_kembali
- tambah stok inventaris

---

## 10. API Dokumentasi

### Endpoint

```http
GET /dokumentasi.php
POST /dokumentasi.php
DELETE /dokumentasi.php
```

### Field

| Field | Type |
|------|------|
| kegiatan_id | int |
| user_id | int |
| jenis_dokumen | string |
| file | file upload |

### Format File

- PDF
- JPG
- PNG
- DOC
- DOCX

### Upload Directory

```text
/uploads/
```

---

## 11. Hak Akses Sistem

### Super Admin
Memiliki akses penuh terhadap seluruh sistem:

- Users
- Kegiatan
- Absensi
- Inventaris
- Peminjaman
- Dokumentasi

### Admin
Memiliki akses:

- Kegiatan
- Absensi
- Inventaris
- Peminjaman
- Dokumentasi

### User
Memiliki akses:

- Login
- Lihat kegiatan
- Absensi
- Peminjaman
- Dokumentasi

---

## 12. Penutup

Backend API Sistem Manajemen Kegiatan Laboratorium telah dirancang agar dapat digunakan oleh frontend website maupun frontend mobile melalui endpoint yang sama, sehingga pengembangan sistem menjadi lebih efisien, terintegrasi, dan mudah dipelihara.