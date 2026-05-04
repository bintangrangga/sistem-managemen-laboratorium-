# DOKUMENTASI API
## Sistem Manajemen Kegiatan Laboratorium

---

## 1. Pendahuluan

Dokumentasi ini berisi daftar endpoint API yang digunakan dalam Sistem Manajemen Kegiatan Laboratorium. API ini berfungsi sebagai penghubung antara backend dengan frontend website maupun mobile menggunakan sistem **REST API berbasis token authorization**.

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

## 4. Authentication System

Sistem menggunakan **Bearer Token Authorization**.

Flow:

1. User login
2. Server generate token
3. Token dikirim ke frontend
4. Frontend menyimpan token
5. Setiap request wajib mengirim Authorization Header

Format Header:

```http
Authorization: Bearer YOUR_TOKEN
```

Contoh:

```http
Authorization: Bearer abc123xyz456token
```

> Semua endpoint **kecuali Login** wajib menggunakan token.

---

## 5. Authentication / Login

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
  "token": "abc123xyz456token",
  "data": {
    "id": 1,
    "nama": "Bintang Rangga Saputra",
    "email": "bintang23@lab.com",
    "role": "super_admin"
  }
}
```

---

## 6. Logout

### Endpoint

```http
POST /logout.php
```

### Header

```http
Authorization: Bearer YOUR_TOKEN
```

### Response

```json
{
  "status": "success",
  "message": "Logout berhasil"
}
```

Setelah logout, token akan dihapus dari database dan tidak bisa digunakan kembali.

---

## 7. API Users

### Endpoint

```http
GET /users.php
POST /users.php
PUT /users.php
DELETE /users.php
```

### Header

```http
Authorization: Bearer YOUR_TOKEN
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

## 8. API Kegiatan

### Endpoint

```http
GET /kegiatan.php
POST /kegiatan.php
PUT /kegiatan.php
DELETE /kegiatan.php
```

### Header

```http
Authorization: Bearer YOUR_TOKEN
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

## 9. API Absensi

### Endpoint

```http
GET /absensi.php
POST /absensi.php
PUT /absensi.php
DELETE /absensi.php
```

### Header

```http
Authorization: Bearer YOUR_TOKEN
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

---

## 10. API Inventaris

### Endpoint

```http
GET /inventaris.php
POST /inventaris.php
PUT /inventaris.php
DELETE /inventaris.php
```

### Header

```http
Authorization: Bearer YOUR_TOKEN
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

## 11. API Peminjaman

### Endpoint

```http
GET /peminjaman.php
POST /peminjaman.php
PUT /peminjaman.php
```

### Header

```http
Authorization: Bearer YOUR_TOKEN
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

#### Saat meminjam
- cek stok
- insert transaksi
- kurangi stok inventaris

#### Saat mengembalikan
- update status
- isi tanggal_kembali
- tambah stok inventaris

---

## 12. API Dokumentasi

### Endpoint

```http
GET /dokumentasi.php
POST /dokumentasi.php
DELETE /dokumentasi.php
```

### Header

```http
Authorization: Bearer YOUR_TOKEN
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

## 13. Hak Akses Sistem

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
- Logout
- Lihat kegiatan
- Absensi
- Peminjaman
- Dokumentasi

---

## 14. Security Features

Fitur keamanan backend:

- Password Hashing
- Token Authorization
- Protected Endpoint
- Logout Token Invalidation
- CORS Enabled
- Role Based Access Ready

---

## 15. Penutup

Backend API Sistem Manajemen Kegiatan Laboratorium telah dirancang agar dapat digunakan oleh frontend website maupun frontend mobile melalui endpoint yang sama, aman, terintegrasi, dan mudah dikembangkan.