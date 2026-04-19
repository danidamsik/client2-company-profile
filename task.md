## Phase 0 - Setup Dependency & Environment
### Task
- Pastikan project Laravel sudah tersedia
- Install Laravel Breeze, vue 3, inertia js
- hapus fitur register default breeze
- Setup authentication dasar dari Breeze
- Install dan konfigurasi Tailwind CSS
- Pastikan Vite berjalan dengan baik
- Konfigurasi file frontend utama
- Konfigurasi file routing awal
- Konfigurasi layout dasar untuk auth
- Pastikan halaman login, register, dan dashboard default dapat diakses
- Pastikan build asset frontend berjalan normal
- Pastikan integrasi Laravel + Vue + Inertia + Tailwind berjalan baik
- Verifikasi project dapat dijalankan tanpa error dasar

## Phase 1 - Setup Migration, Relasi Model & seeder
### Task
- Analisis kebutuhan project website company profile pada file teknikal-document.md
- buat migration sesuai kebutuhan yg ada di file teknikal_dokumen.md
- buat model dan relasi nya (jika ada)
- buat seeder untuk kebutuhan testing nanti

## Phase 2 - Authentication & Authorization
### Task
- karena role hanya 1 yaitu admin, jadi sistem ini tidak butuh role user
- Pastikan hanya user yg sudah login mengakses dashboard admin
- auto redirect jika sudah login
- Pastikan guest dapat melihat seluruh konten publik

## Phase 3 - Design System, Layout & Navigasi Frontend
### Task
    ### Design System Foundation
    - Definisikan typography system (font size, weight, line-height)
    - Definisikan color system sesuai guideline (primary, secondary, accent, neutral)
    - Definisikan spacing system (margin, padding scale)
    - Definisikan border radius dan shadow standar
    - Buat config Tailwind agar sesuai design system

    ### Reusable Components
    - Buat komponen Button (variant: primary, secondary, outline)
    - Buat komponen Input (text, email, textarea)
    - Buat komponen Card (default, hover state)
    - Buat komponen Section Container (wrapper global)
    - Buat komponen Section Heading (title + subtitle)
    - Buat komponen Badge / Label (opsional untuk sertifikasi)
    - Pastikan semua komponen reusable dan konsisten

    ### Layout Structure
    - Buat layout utama untuk guest (MainLayout)
    - Buat struktur halaman berbasis section (bukan page-heavy)
    - Buat sistem container max-width yang konsisten
    - Implementasi grid system untuk layout konten

    ### Navbar & Navigasi
    - Buat navbar utama:
    - Logo perusahaan
    - Navigasi ke section (Home, Layanan, Sertifikasi, Galeri, Contact)
    - CTA button (Hubungi Kami)
    - Tambahkan sticky navbar saat scroll
    - Tambahkan mobile menu (hamburger)
    - Implementasi smooth scroll ke section
    - Highlight menu aktif saat scroll (scroll spy)

    ### Footer
    - Buat footer dengan informasi:
    - Nama perusahaan
    - Kontak (email, WhatsApp)
    - Navigasi cepat
    - Tambahkan copyright
    - Tambahkan CTA kecil di footer

    ### Responsiveness & UX
    - Pastikan mobile-first implementation
    - Optimasi tampilan tablet & desktop
    - Pastikan navigasi mudah digunakan di mobile
    - Pastikan spacing dan readability optimal

    ### Performance & Clean Code
    - Pastikan komponen ringan dan tidak redundant
    - Gunakan lazy load untuk komponen jika diperlukan
    - Pastikan struktur folder frontend rapi:
    - components/
    - layouts/
    - pages/
    - Gunakan naming convention yang konsisten (PascalCase)

---

## Phase 4 - Hero Section & Tentang Kami
### Task
    ### Hero Section
    - Buat hero section sebagai first fold halaman
    - Tambahkan headline utama yang jelas dan kuat
    - Tambahkan subheadline yang menjelaskan jasa perusahaan
    - Tambahkan CTA utama:
    - "Hubungi Kami"
    - "Konsultasi Sekarang"
    - Tambahkan CTA secondary (opsional):
    - "Lihat Layanan"

    ### Visual & Branding
    - Tambahkan visual pendukung:
    - Ilustrasi / foto security profesional
    - Gunakan overlay atau gradient agar teks terbaca jelas
    - Pastikan visual mencerminkan trust & profesionalitas
    - Gunakan warna brand secara konsisten

    ### CTA Integration
    - Integrasikan CTA langsung ke WhatsApp
    - Pastikan CTA terlihat jelas di mobile dan desktop
    - Tambahkan microcopy yang mendorong aksi

    ### Trust Signal (Opsional)
    - Tambahkan highlight singkat:
    - Jumlah klien
    - Pengalaman
    - Sertifikasi
    - Gunakan badge atau highlight kecil

    ### Tentang Kami Section
    - Buat section tentang perusahaan setelah hero
    - Tampilkan:
    - Deskripsi singkat perusahaan
    - Visi / misi / nilai
    - Keunggulan utama
    - Gunakan layout 2 kolom (text + visual)

    ### Content Structure
    - Gunakan bullet point untuk keunggulan
    - Hindari paragraf panjang
    - Gunakan hierarchy heading yang jelas

    ### UX & Readability
    - Pastikan teks mudah dibaca (kontras warna baik)
    - Pastikan spacing antar elemen cukup lega
    - Pastikan section tidak terlalu padat

    ### Animasi & Interaksi
    - Tambahkan animasi ringan:
    - Fade-in saat scroll
    - Hover effect pada CTA
    - Hindari animasi berlebihan

    ### Responsiveness
    - Pastikan hero tetap optimal di mobile
    - Pastikan CTA tetap terlihat tanpa scroll terlalu jauh
    - Pastikan layout tidak pecah di berbagai screen size

    ### Validation
    - Pastikan pesan utama bisa dipahami dalam < 5 detik
    - Pastikan CTA jelas dan tidak ambigu
    - Pastikan user langsung tahu:
    - Apa layanan perusahaan
    - Apa yang harus dilakukan selanjutnya

## Phase 5 - Layanan Dan Sertifikasi
### Task
- Buat section layanan pada halaman utama
- Tampilkan daftar layanan utama perusahaan
- Buat card layanan yang reusable
- Tampilkan judul, deskripsi singkat, dan icon / visual pendukung layanan
- Pastikan informasi layanan mudah dipahami calon klien
- Buat halaman khusus layanan jika dibutuhkan
- Buat section sertifikasi pada halaman utama / halaman khusus
- Tampilkan data sertifikasi perusahaan
- Tampilkan gambar sertifikat dengan layout yang rapi
- Tambahkan judul dan deskripsi sertifikasi
- Pastikan gambar sertifikasi menggunakan storage link
- Pastikan loading gambar optimal
- Pastikan tampilan layanan dan sertifikasi responsive
- Pastikan hierarchy visual jelas dan profesional

## Phase 6 - Galeri Dokumentasi Kegiatan
### Task
- Buat section galeri pada website publik
- Tampilkan dokumentasi kegiatan perusahaan
- Gunakan card / grid galeri yang responsive
- Tampilkan caption singkat pada setiap gambar
- Implementasikan lazy loading pada gambar
- Pastikan gambar terkompresi dan optimal
- Buat halaman galeri jika diperlukan
- Pastikan galeri mudah di-maintain dari dashboard admin

## Phase 7 - Contact Section & Lead Integration
### Task
- Buat section contact pada halaman publik
- Buat form contact dengan field nama, email, pesan
- Validasi semua field wajib diisi
- Implementasikan pengiriman ke WhatsApp
- Implementasikan pengiriman / penyimpanan contact ke email atau database sesuai kebutuhan
- Simpan data pesan ke tabel contacts
- Buat call-to-action yang jelas untuk menghubungi perusahaan
- Pastikan feedback sukses / error tampil dengan jelas
- Pastikan form aman dari input tidak valid
- Sanitasi input form contact
- Pastikan contact section responsive dan mudah digunakan di mobile

## Phase 8 - Dashboard Admin Dasar
### Task
- Buat layout dashboard admin
- Buat sidebar / navigasi dashboard
- Buat halaman dashboard utama
- Tampilkan ringkasan data sederhana jika diperlukan
- Pastikan hanya admin login yang dapat mengakses dashboard
- Pastikan guest tidak dapat mengakses dashboard
- selalu gunakan modal untuk CREATE/UPDATE
- buat toast notification global notifikasi muncul menggunakan event
- Buat struktur halaman CRUD untuk layanan
- Buat struktur halaman CRUD untuk sertifikasi
- Buat struktur halaman CRUD untuk galeri
- Siapkan alur publish / update konten
- Pastikan UI dashboard konsisten dan mudah digunakan

## Phase 9 - CRUD Layanan
### Task
- Buat halaman list layanan pada dashboard
- Buat halaman tambah layanan
- Buat halaman edit layanan
- Buat aksi hapus layanan
- Validasi title dan description
- Pastikan data layanan tersimpan ke database
- Tampilkan notifikasi sukses / gagal
- Tambahkan pagination pada data list
- Pastikan seluruh aksi CRUD berjalan tanpa error dasar

## Phase 10 - CRUD Sertifikasi
### Task
- Buat halaman list sertifikasi pada dashboard
- Buat halaman tambah sertifikasi
- Buat halaman edit sertifikasi
- Buat aksi hapus sertifikasi
- Implementasikan upload gambar sertifikasi
- Validasi file upload hanya jpg/png
- Validasi ukuran file maksimal 2MB
- Simpan file ke storage yang sesuai
- Gunakan storage link untuk menampilkan gambar
- Tampilkan preview gambar jika diperlukan
- Tambahkan pagination pada data list
- Pastikan CRUD sertifikasi berjalan stabil

## Phase 11 - CRUD Galeri
### Task
- Buat halaman list galeri pada dashboard
- Buat halaman tambah galeri
- Buat halaman edit galeri
- Buat aksi hapus galeri
- Implementasikan upload gambar galeri
- Validasi format dan ukuran file upload
- Simpan caption galeri
- Gunakan storage link untuk render gambar
- Tambahkan pagination pada data list
- Pastikan galeri dapat tampil di halaman publik dengan benar

## Phase 12 - Halaman Publik Dinamis
### Task
- Hubungkan landing page dengan data dinamis dari database
- Tampilkan layanan dari tabel services
- Tampilkan sertifikasi dari tabel certifications
- Tampilkan galeri dari tabel galleries
- Pastikan data publik dapat berubah otomatis setelah admin update
- Pastikan fallback tampil aman jika data kosong
- Pastikan performa query efisien
- Rapikan controller / service layer untuk data publik

## Phase 13 - SEO Optimization
### Task
- Setup title dan meta description dinamis setiap halaman
- Tambahkan meta keyword jika diperlukan
- Tambahkan Open Graph basic tags
- Pastikan struktur heading rapi (h1, h2, h3)
- Buat slug / URL yang rapi jika ada halaman detail
- Tambahkan sitemap jika diperlukan
- Tambahkan robots.txt basic
- Optimalkan semantic HTML
- Pastikan halaman cepat diakses
- Pastikan gambar memiliki alt text yang relevan
- Validasi kesiapan SEO on-page dasar

## Phase 14 - UI Polish, Animasi & Responsive Improvement
### Task
- Rapikan konsistensi typography
- Rapikan spacing antar section
- Pastikan warna dominan sesuai guideline (putih, kuning, orange)
- Tambahkan animasi scroll pada guest sesuai kebutuhan
- Pastikan button, card, input, section heading reusable dan konsisten
- Perbaiki hierarchy visual agar lebih profesional
- Optimasi tampilan mobile-first
- Optimasi tampilan tablet dan desktop
- Pastikan navbar dan footer nyaman digunakan di semua device
- Lakukan pengecekan detail visual secara menyeluruh

## Phase 15 - Security & Validation Hardening
### Task
- Pastikan semua form memiliki validasi frontend dan backend
- Pastikan CSRF protection aktif
- Pastikan output aman dari XSS
- Pastikan password menggunakan hashing bawaan Laravel
- Pastikan route dashboard diproteksi middleware auth
- Pastikan upload file aman dan tervalidasi
- Pastikan guest tidak dapat mengakses endpoint admin
- Pastikan sanitasi input berjalan baik
- Lakukan pengecekan error handling dasar

## Phase 16 - Performance & Optimization
### Task
- Implementasikan pagination pada list dashboard
- Pastikan lazy loading gambar aktif pada halaman publik
- Optimalkan ukuran asset frontend
- Pastikan build Vite efisien
- Gunakan caching untuk data yang relevan bila diperlukan
- Siapkan integrasi CDN untuk asset statis bila diperlukan
- Minimalkan query yang tidak perlu
- Pastikan loading halaman tetap cepat
- Lakukan pengecekan performa dasar