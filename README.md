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

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## SabuMarket Product API

REST API ini menggunakan model dan field produk SabuMarket: `name`, `category`,
`description`, `price`, `stock`, `image`, dan `is_active`.

| Method | Endpoint | Akses | Fungsi |
| --- | --- | --- | --- |
| GET | `/api/products` | Publik | Daftar produk aktif, paginasi 15 produk |
| GET | `/api/products/{id}` | Publik | Detail produk aktif |
| POST | `/api/login` | Publik | Login dengan email dan password, menghasilkan Bearer token |
| GET | `/api/user` | Bearer token | Informasi pengguna yang sedang login |
| POST | `/api/logout` | Bearer token | Mencabut token yang sedang digunakan |
| POST | `/api/products` | Bearer token | Membuat produk |
| PUT/PATCH | `/api/products/{id}` | Bearer token | Memperbarui produk |
| DELETE | `/api/products/{id}` | Bearer token | Menghapus produk |

Endpoint autentikasi dan produk menggunakan Laravel Sanctum. Login menerima
`email` dan `password`; gunakan nilai `access_token` dari respons sebagai header
`Authorization: Bearer <access_token>` untuk mengakses `/api/user`, `/api/logout`,
dan endpoint tulis produk. Logout mencabut token yang sedang digunakan.
Permintaan API sebaiknya menyertakan header `Accept: application/json`.
Pembuatan produk memerlukan `name` dan `price`; `stock` default ke `0`, sedangkan
`is_active` default ke `true`. Upload `image` menerima JPG, JPEG, PNG, atau WEBP
dengan ukuran maksimum 2 MB.

## Fitur produk dan invoice SabuMarket

Foto produk yang diunggah lewat dashboard atau API diputar sesuai pilihan,
dipotong dan diubah ukurannya menjadi 1200 x 1200, diberi watermark SabuMarket,
lalu disimpan sebagai WEBP. Untuk menjalankan pemrosesan gambar, aktifkan
ekstensi PHP GD dengan dukungan WebP di konfigurasi PHP XAMPP (`php.ini`).
Dashboard juga memerlukan symbolic link storage publik:

```shell
php artisan storage:link
```

Pada halaman detail produk, pengguna yang login dapat memesan produk sesuai stok
yang tersedia. Sistem menyimpan snapshot harga dan nama produk, mengurangi stok,
lalu mengunduh invoice PDF berstatus belum dibayar. Invoice hanya dapat diunduh
oleh pemilik pesanan. Jalankan migrasi untuk membuat tabel pesanan:

```shell
php artisan migrate
```

Contoh membuat produk dengan JSON:

```http
POST /api/products
Accept: application/json
Content-Type: application/json

{
  "name": "Kopi Sabu",
  "category": "Minuman",
  "description": "Kopi lokal",
  "price": 18000,
  "stock": 12
}
```
