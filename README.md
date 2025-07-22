# Multi-Tenant Laravel Application dengan Livewire

Aplikasi multi-tenant sederhana yang dibangun dengan Laravel 11 dan Livewire 3. Aplikasi ini mendemonstrasikan bagaimana membuat sistem multi-tenant berbasis subdomain di mana setiap tenant memiliki data yang terpisah.

## Fitur

- **Multi-tenant Architecture**: Setiap tenant diidentifikasi melalui subdomain
- **Tenant Isolation**: Data setiap tenant terpisah secara otomatis
- **Role-based Access Control**: 3 level role (superadmin, admin, user)
- **Module Management System**: SuperAdmin dapat mengaktifkan/menonaktifkan module untuk setiap tenant
- **Dynamic Module Access**: User tenant hanya dapat mengakses module yang diaktifkan
- **5 Sample Modules**: Blog, CRM, Inventory, Analytics, dan Support
- **Module-Specific Routes**: Setiap module memiliki routes dan middleware access control
- **Livewire Components**: Interface yang reaktif untuk manajemen
- **CRUD Operations**: Operasi lengkap untuk setiap module
- **User Management**: Admin dapat mengelola users dalam tenant mereka
- **Tenant Management**: SuperAdmin dapat mengelola semua tenant dan module assignments
- **Analytics Dashboard**: Dashboard analitik untuk melihat aktivitas tenant
- **Responsive Design**: UI yang responsive menggunakan Tailwind CSS

## Sistem Role

### 1. **SuperAdmin**
- Dapat mengakses semua tenant dan data
- Dapat membuat, edit, dan hapus tenant
- Dapat mengelola semua users di semua tenant
- **Dapat mengelola module global dan mengaktifkan/menonaktifkan module untuk tenant**
- Akses melalui route `/admin/*`
- Tidak terikat pada tenant tertentu

### 2. **Admin** (per tenant)
- Dapat mengelola users dalam tenant mereka
- **Dapat mengakses semua module yang diaktifkan untuk tenant mereka**
- **Dapat mengelola data dalam module yang tersedia (Blog, CRM, Inventory, Support)**
- Tidak dapat mengakses data tenant lain
- Terikat pada tenant tertentu

### 3. **User** (per tenant)
- **Dapat mengakses module yang diaktifkan untuk tenant mereka**
- **Dapat mengelola data mereka sendiri dalam module yang tersedia**
- Tidak dapat mengelola users lain
- Akses terbatas berdasarkan module yang diaktifkan

## Struktur Multi-Tenant

### Komponen Utama:

1. **Tenant Model**: Menyimpan informasi tenant (nama, subdomain, dll)
2. **Module Model**: Menyimpan informasi module global yang tersedia
3. **Tenant-Module Relationship**: Many-to-many relationship untuk mengatur module per tenant
4. **Tenant Middleware**: Mendeteksi tenant berdasarkan subdomain dan mengatur konteks
5. **Module Access Middleware**: Mengontrol akses ke module berdasarkan tenant
6. **BelongsToTenant Trait**: Memastikan model secara otomatis di-scope ke tenant aktif
7. **Module Management Components**: Interface untuk SuperAdmin mengelola module

### Cara Kerja:

1. User mengakses aplikasi melalui subdomain (contoh: `demo.localhost:8000`)
2. Tenant Middleware mendeteksi subdomain dan mengidentifikasi tenant
3. Module Access Middleware memeriksa apakah tenant memiliki akses ke module yang diminta
4. Semua query database secara otomatis di-scope ke tenant tersebut
5. User hanya bisa melihat dan mengelola data milik tenant-nya dalam module yang diaktifkan
6. SuperAdmin dapat mengaktifkan/menonaktifkan module untuk setiap tenant melalui admin panel

## Module Management System

### Available Modules:

1. **Blog Module** (`/modules/blog`)
   - Manajemen artikel dan konten blog
   - CRUD operations untuk blog posts
   - Status: draft, published
   - Features: title, excerpt, content, publish date

2. **CRM Module** (`/modules/crm`)
   - Customer Relationship Management
   - Manajemen kontak dan leads
   - Status: lead, prospect, customer, inactive
   - Features: contact info, company, deal value

3. **Inventory Module** (`/modules/inventory`)
   - Manajemen stok dan produk
   - Tracking inventory levels
   - Features: SKU, pricing, stock quantity, suppliers

4. **Analytics Module** (`/modules/analytics`)
   - Dashboard analitik dan laporan
   - Overview statistik tenant
   - Charts dan visualisasi data

5. **Support Module** (`/modules/support`)
   - Sistem tiket support pelanggan
   - Priority levels: low, medium, high, urgent
   - Status tracking: open, in_progress, resolved, closed

### Module Access Control:

- **SuperAdmin**: Akses ke semua module dan dapat mengelola module assignments
- **Tenant Users**: Hanya dapat mengakses module yang diaktifkan untuk tenant mereka
- **Dynamic Navigation**: Menu navigasi menampilkan hanya module yang tersedia
- **Middleware Protection**: Setiap module route dilindungi oleh `module.access` middleware

## Authentication System

### Login Features:
- **Secure Login**: Standard Laravel authentication dengan session management
- **Quick Login**: Demo buttons untuk testing dengan satu klik
- **Auto Redirect**: User diarahkan ke halaman yang sesuai berdasarkan role dan tenant
- **Guest Protection**: User yang sudah login tidak bisa mengakses halaman login
- **Auth Protection**: Semua routes dilindungi dengan auth middleware

### Login Flow:
1. **Guest User** → Diarahkan ke `/login`
2. **Login Success** → Redirect berdasarkan role:
   - SuperAdmin → `/admin/tenants`
   - Tenant User → `/?tenant={subdomain}`
3. **Logout** → Kembali ke `/login` dengan session cleared

### Security Features:
- Session regeneration setelah login
- CSRF protection pada form login
- Password hashing dengan bcrypt
- Middleware protection untuk semua protected routes

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

### Login System:
1. **Halaman Login**: `http://127.0.0.1:8000/login`
2. **Quick Login Buttons**: Klik tombol untuk login cepat dengan demo accounts
3. **Auto Redirect**: Setelah login, user akan diarahkan ke halaman yang sesuai:
   - **SuperAdmin** → `/admin/tenants`
   - **Tenant Users** → `/?tenant={subdomain}` (dashboard tenant)

### Testing Multi-Tenant

Aplikasi ini membuat 3 tenant demo:

1. **Demo Company** - Subdomain: `demo`
2. **Test Corporation** - Subdomain: `test`
3. **Sample Inc** - Subdomain: `sample`

### Cara Mengakses:

#### SuperAdmin Panel:
- `http://127.0.0.1:8000/admin/tenants` - Kelola semua tenant
- `http://127.0.0.1:8000/admin/modules` - Kelola module global
- `http://127.0.0.1:8000/admin/tenant-modules` - Assign module ke tenant
- `http://127.0.0.1:8000/admin/users` - Kelola semua users

#### Tenant Admin/User Panel:
- `http://127.0.0.1:8000?tenant=demo` - Demo Company (Blog, CRM, Analytics)
- `http://127.0.0.1:8000?tenant=test` - Test Corporation (Blog, Inventory, Support)
- `http://127.0.0.1:8000?tenant=sample` - Sample Inc (CRM, Inventory, Analytics, Support)

#### Module Access per Tenant:
- `http://127.0.0.1:8000/modules/blog?tenant=demo` - Blog module (Demo)
- `http://127.0.0.1:8000/modules/crm?tenant=demo` - CRM module (Demo)
- `http://127.0.0.1:8000/modules/analytics?tenant=demo` - Analytics module (Demo)
- `http://127.0.0.1:8000/users?tenant=demo` - User management (Demo)

### Demo Accounts:
- **SuperAdmin**: `superadmin@example.com` / `password`
- **Demo Company Admin**: `admin@demo.com` / `password` (Blog, CRM, Analytics)
- **Test Corporation Admin**: `admin@test.com` / `password` (Blog, Inventory, Support)
- **Sample Inc Admin**: `admin@sample.com` / `password` (CRM, Inventory, Analytics, Support)
- **Demo User**: `user@demo.com` / `password`
- **Test User**: `user@test.com` / `password`
- **Sample User**: `user@sample.com` / `password`

## Testing Module Management

### Test SuperAdmin Module Management:
1. Login sebagai SuperAdmin (`superadmin@example.com`)
2. Akses `/admin/modules` untuk mengelola module global
3. Akses `/admin/tenant-modules` untuk mengatur module per tenant
4. Test enable/disable module untuk tenant tertentu

### Test Tenant Module Access:
1. Login sebagai tenant admin (contoh: `admin@demo.com`)
2. Cek dashboard untuk melihat module yang tersedia
3. Test akses ke module yang diaktifkan (Blog, CRM, Analytics untuk Demo)
4. Test akses ditolak ke module yang tidak diaktifkan (Inventory, Support untuk Demo)

### Test Module Functionality:
1. **Blog Module**: Create, edit, delete blog posts
2. **CRM Module**: Manage contacts and leads
3. **Inventory Module**: Manage products and stock
4. **Analytics Module**: View tenant statistics
5. **Support Module**: Create and manage support tickets

## Fitur PostManager

- **Buat Post Baru**: Klik tombol "Buat Post Baru"
- **Edit Post**: Klik tombol "Edit" pada post yang ingin diubah
- **Hapus Post**: Klik tombol "Hapus" dengan konfirmasi
- **Pagination**: Otomatis menampilkan pagination jika post lebih dari 5
- **Real-time Updates**: Interface otomatis update tanpa refresh halaman

## Fitur TenantManager (SuperAdmin Only)

- **Buat Tenant Baru**: Membuat tenant dengan subdomain unik
- **Edit Tenant**: Mengubah nama dan subdomain tenant
- **Hapus Tenant**: Menghapus tenant beserta semua data (users, posts)
- **View Tenant**: Link langsung ke website tenant
- **User Count**: Melihat jumlah users per tenant

## Fitur UserManager (Admin & SuperAdmin)

- **Buat User Baru**: Menambah user dengan role (admin/user)
- **Edit User**: Mengubah data user dan role
- **Hapus User**: Menghapus user (kecuali diri sendiri)
- **Role Management**: Mengatur role user (admin dapat membuat admin lain)
- **Tenant Scoping**: Admin hanya melihat users dari tenant mereka

## Arsitektur Kode

### Models
- `Tenant`: Model untuk data tenant dengan relasi ke modules
- `Module`: Model untuk data module global
- `User`: Model user dengan relasi ke tenant
- `Post`: Model post dengan tenant scoping (legacy)
- `BlogPost`: Model untuk blog module
- `CrmContact`: Model untuk CRM module
- `InventoryProduct`: Model untuk inventory module
- `SupportTicket`: Model untuk support module

### Middleware
- `TenantMiddleware`: Mendeteksi dan mengatur konteks tenant
- `ModuleAccessMiddleware`: Mengontrol akses ke module berdasarkan tenant

### Traits
- `BelongsToTenant`: Automatic scoping untuk model yang belong to tenant

### Livewire Components
- `PostManager`: Komponen untuk CRUD operations posts (legacy)
- `ModuleManager`: Komponen untuk SuperAdmin mengelola module global
- `TenantModuleManager`: Komponen untuk SuperAdmin mengatur module per tenant
- `ModuleDashboard`: Dashboard untuk menampilkan module yang tersedia
- `UserManager`: Komponen untuk mengelola users per tenant
- `TenantManager`: Komponen untuk SuperAdmin mengelola tenant

### Controllers
- `Modules\BlogController`: Controller untuk blog module
- `Modules\CrmController`: Controller untuk CRM module
- `Modules\InventoryController`: Controller untuk inventory module
- `Modules\SupportController`: Controller untuk support module
- `Modules\AnalyticsController`: Controller untuk analytics module

## Customization

### Menambah Module Baru:

1. **Buat Module di Database**:
```php
\App\Models\Module::create([
    'name' => 'Your Module',
    'slug' => 'your-module',
    'description' => 'Description of your module',
    'icon' => 'fas fa-your-icon',
    'color' => '#FF5733',
    'is_active' => true,
    'sort_order' => 10,
]);
```

2. **Buat Model dengan Tenant Scoping**:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class YourModuleModel extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'tenant_id', // pastikan ada ini
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

3. **Buat Controller**:
```php
<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\YourModuleModel;

class YourModuleController extends Controller
{
    public function index()
    {
        $items = YourModuleModel::latest()->paginate(10);
        return view('modules.your-module.index', compact('items'));
    }

    // Add other CRUD methods...
}
```

4. **Tambahkan Routes dengan Module Access**:
```php
Route::middleware(['module.access:your-module'])->prefix('your-module')->name('modules.your-module.')->group(function () {
    Route::get('/', [YourModuleController::class, 'index'])->name('index');
    // Add other routes...
});
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
