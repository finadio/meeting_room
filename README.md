<p align="center"><img src="public/img/msa.png" width="300" alt="Logo PT BPR MSA"></p>
<p align="center">
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Booking Meeting Room PT BPR MSA

## Tentang Project

Aplikasi **Booking Meeting Room** ini dikembangkan oleh tim mahasiswa sebagai bagian dari tugas kerja praktik di **PT BPR MSA**. Sistem ini bertujuan untuk memudahkan proses reservasi ruang rapat secara online, meningkatkan efisiensi penggunaan fasilitas, serta mendukung digitalisasi proses administrasi di lingkungan PT BPR MSA.

## Tujuan
- Mempermudah karyawan dan manajemen dalam melakukan pemesanan ruang meeting secara online.
- Menyediakan sistem monitoring status ruangan secara real-time.
- Mendukung transparansi dan efisiensi dalam pengelolaan fasilitas kantor.
- Menjadi bagian dari transformasi digital di PT BPR MSA.

## Fitur Utama
- **Booking Ruang Meeting Online**: Reservasi ruang meeting dengan jadwal yang fleksibel dan transparan.
- **Agenda Harian Otomatis**: Pengiriman agenda harian ke WhatsApp untuk pengingat dan koordinasi.
- **Status Ruangan Real-Time**: Monitoring status pemakaian ruangan (sedang dipakai, selesai, extend, dsb).
- **Manajemen User & Admin**: Registrasi, login, pengelolaan profil, dan dashboard admin untuk monitoring aktivitas.
- **Notifikasi**: Notifikasi otomatis ke user/admin terkait status booking dan agenda.
- **Manajemen Fasilitas**: Admin dapat menambah, mengedit, dan menghapus data fasilitas ruang meeting.
- **Kontak & Layanan**: Formulir kontak untuk komunikasi internal dan eksternal.
- **Kalender & Riwayat Booking**: Tampilan kalender dan riwayat pemesanan untuk memudahkan tracking penggunaan ruangan.
- **Responsive Design**: Tampilan aplikasi yang nyaman diakses dari desktop maupun mobile.

## Teknologi
- **Laravel 10** (Backend & Blade Templating)
- **MySQL/MariaDB** (Database)
- **Bootstrap 5, Boxicons** (Frontend)
- **WhatsApp API** (Notifikasi agenda harian)

## Cara Instalasi

1. **Clone repository ini:**
```bash
git clone https://github.com/finadio/meeting_room.git
```
2. **Masuk ke folder project:**
```bash
cd meeting_room
```
3. **Install dependencies:**
```bash
composer install
```
4. **Copy file .env dan konfigurasi:**
```bash
cp .env.example .env
```
5. **Generate application key:**
```bash
php artisan key:generate
```
6. **Atur konfigurasi database dan setting lain di file .env**
7. **Jalankan migrasi dan seeder:**
```bash
php artisan migrate --seed
```

## Menjalankan Project
Untuk menjalankan server Laravel:
```bash
php artisan serve
```
Akses aplikasi di [http://localhost:8000](http://localhost:8000)

## Kontribusi & Tim
Project ini dikembangkan oleh mahasiswa kerja praktik di PT BPR MSA sebagai bagian dari implementasi digitalisasi proses bisnis. Untuk pertanyaan atau kontribusi, silakan hubungi tim pengembang atau admin PT BPR MSA.

## Lisensi
Aplikasi ini menggunakan [MIT license](https://opensource.org/licenses/MIT).
