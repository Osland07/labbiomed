<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Sistem Laboratorium Biomedik

Sistem manajemen laboratorium yang dilengkapi dengan fitur QR code untuk memudahkan proses check-in dan check-out kunjungan.

## Fitur Utama

### Sistem Kunjungan dengan QR Code
- **QR Code Check-in**: Generate QR code untuk setiap ruangan untuk proses check-in
- **QR Code Check-out**: Generate QR code untuk setiap ruangan untuk proses check-out
- **QR Code Scanner**: Scanner QR code menggunakan kamera untuk akses cepat
- **Dashboard Kunjungan**: Tampilan semua ruangan dengan QR code dalam satu halaman
- **Riwayat Kunjungan**: Pantau riwayat kunjungan pengguna

### Cara Menggunakan QR Code

#### 1. Generate QR Code
- Admin dapat mengakses dropdown "QR Code" di halaman admin kunjungan
- Pilih "Check-in [Nama Ruangan]" atau "Check-out [Nama Ruangan]"
- QR code akan ditampilkan dengan informasi ruangan

#### 2. Scan QR Code
- Buka aplikasi QR scanner di smartphone
- Arahkan kamera ke QR code yang ditampilkan
- Klik link yang muncul untuk akses langsung ke halaman check-in/check-out

#### 3. Manual Access
- Akses langsung ke halaman check-in/check-out melalui tombol manual
- Atau gunakan dashboard kunjungan untuk melihat semua ruangan

### Halaman QR Code

#### QR Code Check-in (`/kunjungan/qr/checkin/{ruangan_id}`)
- Menampilkan QR code untuk check-in ke ruangan tertentu
- Informasi ruangan dan instruksi penggunaan
- Tombol akses manual dan navigasi ke QR code check-out

#### QR Code Check-out (`/kunjungan/qr/checkout/{ruangan_id}`)
- Menampilkan QR code untuk check-out dari ruangan tertentu
- Informasi ruangan dan instruksi penggunaan
- Tombol akses manual dan navigasi ke QR code check-in

#### QR Code Scanner (`/kunjungan/scan-qr`)
- Scanner QR code menggunakan kamera
- Input manual URL QR code
- Redirect otomatis ke halaman yang sesuai

#### Dashboard Kunjungan (`/kunjungan/dashboard`)
- Tampilan semua ruangan dengan QR code check-in dan check-out
- Statistik kunjungan (total ruangan, kunjungan hari ini, sedang di lab)
- Quick actions untuk akses cepat

### Teknologi QR Code

#### Library yang Digunakan
- **QRCode.js**: Untuk generate QR code di frontend
- **HTML5-QRCode**: Untuk scanner QR code menggunakan kamera

#### Implementasi
- QR code berisi URL langsung ke halaman check-in/check-out
- Validasi QR code di backend untuk keamanan
- Fallback ke input manual jika scanner tidak tersedia

### Keamanan
- Validasi QR code di backend
- Pengecekan ruangan dan tipe QR code
- Redirect yang aman ke halaman yang sesuai

### Responsive Design
- QR code responsive untuk berbagai ukuran layar
- Interface yang user-friendly untuk mobile dan desktop
- Optimized untuk penggunaan di smartphone

## Instalasi dan Penggunaan

1. Clone repository
2. Install dependencies: `composer install`
3. Setup database dan environment
4. Run migrations: `php artisan migrate`
5. Seed data: `php artisan db:seed`
6. Start server: `php artisan serve`

## Akses QR Code

- **Dashboard Kunjungan**: `/kunjungan/dashboard`
- **QR Code Check-in**: `/kunjungan/qr/checkin/{ruangan_id}`
- **QR Code Check-out**: `/kunjungan/qr/checkout/{ruangan_id}`
- **QR Code Scanner**: `/kunjungan/scan-qr`

## Dependencies

- Laravel 10.x
- Tailwind CSS
- QRCode.js
- HTML5-QRCode
- Font Awesome (untuk icons)
