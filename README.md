# Multi-Tenant Laravel Application dengan Livewire

Aplikasi multi-tenant sederhana yang dibangun dengan Laravel 11 dan Livewire 3. Aplikasi ini mendemonstrasikan bagaimana membuat sistem multi-tenant berbasis subdomain di mana setiap tenant memiliki data yang terpisah.

## Fitur

- **Multi-tenant Architecture**: Setiap tenant diidentifikasi melalui subdomain
- **Tenant Isolation**: Data setiap tenant terpisah secara otomatis
- **Livewire Components**: Interface yang reaktif untuk manajemen posts
- **CRUD Operations**: Buat, baca, update, dan hapus posts per tenant
- **Responsive Design**: UI yang responsive menggunakan Tailwind CSS

## Struktur Multi-Tenant

### Komponen Utama:

1. **Tenant Model**: Menyimpan informasi tenant (nama, subdomain, dll)
2. **Tenant Middleware**: Mendeteksi tenant berdasarkan subdomain dan mengatur konteks
3. **BelongsToTenant Trait**: Memastikan model secara otomatis di-scope ke tenant aktif
4. **PostManager Livewire Component**: Interface untuk mengelola posts per tenant

### Cara Kerja:

1. User mengakses aplikasi melalui subdomain (contoh: `demo.localhost:8000`)
2. Middleware mendeteksi subdomain dan mengidentifikasi tenant
3. Semua query database secara otomatis di-scope ke tenant tersebut
4. User hanya bisa melihat dan mengelola data milik tenant-nya

## Instalasi

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite (default database)

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone <repository-url>
   cd multitenant-example
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup database**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   php artisan db:seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start server**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

## Penggunaan

### Testing Multi-Tenant

Aplikasi ini membuat 3 tenant demo:

1. **Demo Company** - Subdomain: `demo`
2. **Test Corporation** - Subdomain: `test`  
3. **Sample Inc** - Subdomain: `sample`

### Cara Mengakses:

#### Untuk Development (localhost):
- Kunjungi: `http://localhost:8000?tenant=demo`
- Atau: `http://localhost:8000?tenant=test`
- Atau: `http://localhost:8000?tenant=sample`

#### Untuk Production (dengan subdomain real):
- `http://demo.yourdomain.com`
- `http://test.yourdomain.com`
- `http://sample.yourdomain.com`

### Default Users:
- Email: `admin@demo.com` / Password: `password`
- Email: `admin@test.com` / Password: `password`
- Email: `admin@sample.com` / Password: `password`

## Fitur PostManager

- **Buat Post Baru**: Klik tombol "Buat Post Baru"
- **Edit Post**: Klik tombol "Edit" pada post yang ingin diubah
- **Hapus Post**: Klik tombol "Hapus" dengan konfirmasi
- **Pagination**: Otomatis menampilkan pagination jika post lebih dari 5
- **Real-time Updates**: Interface otomatis update tanpa refresh halaman

## Arsitektur Kode

### Models
- `Tenant`: Model untuk data tenant
- `User`: Model user dengan relasi ke tenant
- `Post`: Model post dengan tenant scoping

### Middleware
- `TenantMiddleware`: Mendeteksi dan mengatur konteks tenant

### Traits
- `BelongsToTenant`: Automatic scoping untuk model yang belong to tenant

### Livewire Components
- `PostManager`: Komponen untuk CRUD operations posts

## Customization

### Menambah Model Baru dengan Tenant Scoping:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class YourModel extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'name',
        'description',
        'tenant_id', // pastikan ada ini
    ];
}
```

### Menambah Migration untuk Model Baru:

```php
Schema::create('your_models', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});
```

## Keamanan

- **Tenant Isolation**: Data antar tenant tidak bisa dicampur
- **Automatic Scoping**: Semua query otomatis di-scope ke tenant aktif
- **404 untuk Tenant Tidak Valid**: Jika subdomain tidak ditemukan, akan error 404

## Teknologi yang Digunakan

- **Laravel 11**: PHP framework
- **Livewire 3**: Frontend framework untuk Laravel
- **Tailwind CSS**: Utility-first CSS framework
- **SQLite**: Default database (bisa diganti MySQL/PostgreSQL)
- **Vite**: Build tool untuk assets

## Pengembangan Lanjutan

Untuk production, Anda mungkin ingin menambahkan:

1. **Authentication System**: Login/register per tenant
2. **Tenant Registration**: Interface untuk mendaftarkan tenant baru
3. **Custom Domains**: Support untuk custom domain per tenant
4. **Database per Tenant**: Separate database untuk setiap tenant
5. **Admin Panel**: Interface untuk mengelola semua tenant
6. **Billing System**: Sistem pembayaran per tenant
7. **API Support**: REST API dengan tenant scoping

## Troubleshooting

### Database Connection Error
Pastikan file `database/database.sqlite` ada dan readable.

### Assets Not Loading
Jalankan `npm run build` untuk build ulang assets.

### Tenant Not Found Error
Pastikan seeder sudah dijalankan dengan `php artisan db:seed`.

## Kontribusi

Silakan fork repository ini dan buat pull request untuk kontribusi Anda.

## Lisensi

Open source project ini menggunakan MIT License.

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
