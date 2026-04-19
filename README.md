# UTP TIS - Ecommerce API
**Nama:** Gede Raditya Dharma Putra Ayudia  
**NIM:** 245150700111028

Ecommerce-like Backend API sederhana menggunakan Laravel dengan mock data JSON (tanpa database).

---

## 🚀 Cara Menjalankan Project

### 1. Clone repository
```bash
git clone https://github.com/rdtydhrm/UTP-Gede-Raditya-Dharma-Putra-Ayudia-TIS.git
cd UTP-Gede-Raditya-Dharma-Putra-Ayudia-TIS
```

### 2. Install dependencies
```bash
composer install
```

### 3. Copy file environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Jalankan server
```bash
php artisan serve
```

### 5. Generate Swagger docs
```bash
php artisan l5-swagger:generate
```

### 6. Buka Swagger UI
http://localhost:8000/api/documentation

---

## 📋 List Endpoint

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | /api/items | Tampilkan semua item |
| GET | /api/items/{id} | Tampilkan item berdasarkan ID |
| POST | /api/items | Tambah item baru |
| PUT | /api/items/{id} | Update seluruh data item |
| PATCH | /api/items/{id} | Update sebagian data item |
| DELETE | /api/items/{id} | Hapus item |

---

## 📦 Contoh Request Body

### POST /api/items & PUT /api/items/{id}
```json
{
  "nama_barang": "Laptop",
  "harga": 15000000,
  "stok": 10
}
```

### PATCH /api/items/{id} (boleh sebagian)
```json
{
  "harga": 20000000
}
```

---

## ✅ Contoh Response Berhasil

```json
{
  "success": true,
  "message": "Item berhasil ditambahkan",
  "data": {
    "id": 1,
    "nama_barang": "Laptop",
    "harga": 15000000,
    "stok": 10,
    "created_at": "2026-04-19 10:00:00",
    "updated_at": "2026-04-19 10:00:00"
  }
}
```

## ❌ Contoh Response Error (ID tidak ditemukan)

```json
{
  "success": false,
  "message": "Item dengan ID 99 tidak Ditemukan"
}
```

---

## 📄 Akses Dokumentasi Swagger

### Cara 1 - Jalankan lokal
Setelah server berjalan, buka:
http://localhost:8000/api/documentation

### Cara 2 - Via Swagger Editor Online
1. Buka **https://editor.swagger.io**
2. Klik **File** → **Import File**
3. Upload file `storage/api-docs/api-docs.json` dari repository ini
4. Dokumentasi lengkap akan tampil