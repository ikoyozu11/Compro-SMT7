# User Admin KOMINFO

## Daftar User Admin

### 1. Admin Pertama
- **Username**: `admin`
- **Password**: `admin123`
- **Nama**: Admin
- **Role**: admin

### 2. Admin Kedua
- **Username**: `admin2`
- **Password**: `admin123`
- **Nama**: Admin 2
- **Role**: admin

## Cara Menambahkan User Admin Baru

### Menggunakan Seeder (Otomatis)
User admin akan otomatis dibuat saat menjalankan:
```bash
php artisan migrate:fresh --seed
```

### Menggunakan Command Artisan
Untuk menambahkan admin baru secara manual:
```bash
php artisan admin:create {username} {password} {name}
```

Contoh:
```bash
php artisan admin:create admin3 admin123 "Admin 3"
```

### Menggunakan Tinker
```bash
php artisan tinker
```

Kemudian masukkan:
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'username' => 'admin3',
    'password' => Hash::make('admin123'),
    'name' => 'Admin 3',
    'role' => 'admin',
    'status' => 1
]);
```

## Fitur Admin

Setelah login sebagai admin, Anda dapat mengakses:

1. **Generate Link Penelitian**: `/admin/penelitian/link`
2. **Daftar Penelitian**: `/admin/penelitian`
3. **Detail Penelitian**: `/admin/penelitian/{id}`
4. **Manajemen User**: `/admin/master/user`
5. **Manajemen Lokasi**: `/admin/master/lokasi`
6. **Dan fitur admin lainnya**

## Keamanan

- Password di-hash menggunakan bcrypt
- Role-based access control
- Status user untuk aktivasi/deaktivasi 