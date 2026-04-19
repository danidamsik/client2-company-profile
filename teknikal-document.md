# Technical Document - Website Company Profile

## 1. Project Overview

**Nama Project:**
Website Company Profile PT Secure Guard Indonesia

**Tagline:**
Solusi Keamanan Profesional & Terpercaya

**Deskripsi:**
Website ini merupakan platform company profile untuk perusahaan jasa security yang bertujuan menampilkan informasi perusahaan, layanan, sertifikasi, serta mempermudah calon klien untuk menghubungi perusahaan melalui WhatsApp dan email. Website juga dilengkapi dashboard admin untuk mengelola konten secara dinamis.

**Tujuan:**
- Menampilkan profil perusahaan secara profesional
- Meningkatkan kredibilitas melalui sertifikasi
- Mendapatkan leads dari calon klien
- Meningkatkan visibilitas di mesin pencari (SEO)

**Target Pengguna:**
- Perusahaan yang membutuhkan jasa security
- Instansi / event organizer

### Scope

**In Scope:**
- Website company profile (multi halaman)
- Halaman Sertifikasi
- Halaman layanan
- Contact via WhatsApp & Email
- Dashboard admin (CMS sederhana)
- SEO optimization
- tidak ada role user di sistem ini karena hanya ada admin dan guest

**Out of Scope:**
- Sistem pembayaran online
- Aplikasi mobile
- Sistem manajemen karyawan internal

---

## 2. Problem & Solution

**Problem:**
- Perusahaan belum memiliki media online profesional
- Sulit mendapatkan klien dari internet
- Informasi layanan tidak terstruktur
- Tidak ada sistem pengelolaan konten

**Solution:**
- Membangun website company profile modern
- Integrasi WhatsApp & email untuk lead generation
- Menyediakan dashboard admin untuk update konten
- Implementasi SEO untuk meningkatkan traffic

---

## 3. Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Frontend** | Vue 3, Inertia.js, Tailwind CSS |
| **Backend** | Laravel |
| **Database** | MySQL |

---

## 4. Core Features

| Fitur | Deskripsi |
|-------|-----------|
| Landing Page | Halaman utama berisi profil singkat dan CTA |
| Tentang Kami | Informasi perusahaan, visi misi |
| Layanan | Daftar layanan security |
| Sertifikasi | Menampilkan sertifikat perusahaan |
| Galeri | Dokumentasi kegiatan |
| Contact | Form kirim ke WhatsApp & email |
| Dashboard Admin | Kelola konten website |
| SEO Optimization | Optimasi agar muncul di Google |

---

## 5. User Flow

### Admin Flow
1. Login ke dashboard
2. Mengelola konten (layanan, sertifikasi, galeri)
3. Publish perubahan
4. Logout

### guest Flow
1. guest membuka website
2. Melihat layanan & sertifikasi
3. Tertarik → klik contact
4. Menghubungi via WhatsApp / email

---

## 6. Database Schema
users {
  id           BIGINT
  name         VARCHAR
  email        VARCHAR
  password     VARCHAR
  created_at   TIMESTAMP
  updated_at   TIMESTAMP
}

services {
  id           BIGINT
  title        VARCHAR
  description  TEXT
  created_at   TIMESTAMP
  updated_at   TIMESTAMP
}

certifications {
  id           BIGINT
  title        VARCHAR
  image        VARCHAR
  description  TEXT
  created_at   TIMESTAMP
  updated_at   TIMESTAMP
}

galleries {
  id           BIGINT
  image        VARCHAR
  caption      VARCHAR
  created_at   TIMESTAMP
}

contacts {
  id           BIGINT
  name         VARCHAR
  email        VARCHAR
  message      TEXT
  created_at   TIMESTAMP
}
```

---

## 7. Authentication & Authorization

**Authentication:**
- [x] Laravel Breeze

**Authorization:**
- Role-Based Access Control (RBAC)
- Middleware

**Roles:**

| Role | Akses |
|------|-------|
| Admin | Full akses dashboard |
| guest | Hanya akses website publik |

---

## 8. Business Rules & Validation

- Email harus unik
- Form contact wajib diisi semua field
- Admin bisa akses dashboard/halaman utama
- Upload gambar harus format valid (jpg/png)
- Maks ukuran upload 2MB
- gunakan storage link untuk menampilkan gambar

---

## 9. UI Component & Design System

**Framework:** Tailwind CSS

**Components:**
- Button
- Input
- Modal
- Table
- Navbar
- Card
- Hero Section

**Guidelines:**
- Mobile-first design
- Warna dominan: putih, kuning, orange
- UI profesional & minimalis
- Komponen reusable
- animasi scroll pada guest

---

## 10. Security

- CSRF Protection (Laravel default)
- XSS Protection melalui Vue binding
- Validasi input (frontend & backend)
- Password hashing menggunakan bcrypt
- Sanitasi input form contact

---

## 11. Performance & Scalability

- Pagination pada data list
- Lazy loading gambar
- Image compression
- Caching (Laravel Cache)
- CDN untuk asset

---

## 12. Testing Strategy

**Backend:**
- Unit Testing (PHPUnit)
- Feature Testing

**Frontend:**
- Vitest

---

## 13. Environment & Configuration

**Environment:**
- Local
- Staging
- Production

**Konfigurasi Utama (`.env`):**

```env
APP_NAME=Security Company Profile
APP_ENV=production
APP_KEY=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=security_web
DB_USERNAME=root
DB_PASSWORD=
```

---

## 14. Coding Conventions

### Backend (Laravel)
- Standar penulisan: **PSR-12**
- Arsitektur: **Service Layer**
- Penamaan kolom database: `snake_case`
- Penamaan variabel PHP: `camelCase`

### Frontend (Vue)
- Gunakan **Composition API**
- Penamaan komponen: `PascalCase`
- Props dan emits harus didefinisikan dengan jelas
