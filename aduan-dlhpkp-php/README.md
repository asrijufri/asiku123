# Aduan Masyarakat DLHKP - Kabupaten Tojo Una-Una

Sistem Pengaduan Masyarakat untuk Dinas Lingkungan Hidup, Perumahan, Kawasan Permukiman dan Pertanahan Kabupaten Tojo Una-Una.

## Fitur Utama

- **Beranda**: Statistik real-time pengaduan dan informasi kategori
- **Buat Pengaduan**: Formulir lengkap dengan upload foto/bukti
- **Daftar Pengaduan**: Lihat semua pengaduan dengan filter dan pencarian
- **Cek Status**: Lacak status pengaduan menggunakan kode unik
- **Detail Pengaduan**: Lihat detail lengkap dan riwayat status

## Teknologi

- **Backend**: PHP 7.4+ (MySQLi dengan Prepared Statements)
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Styling**: Custom CSS dengan desain modern dan responsif
- **Icons**: Font Awesome 6

## Instalasi

### 1. Persiapan Database

```sql
-- Import database
mysql -u root -p < database.sql
```

Atau buka phpMyAdmin dan import file `database.sql`.

### 2. Konfigurasi

Edit file `includes/config.php` sesuai dengan konfigurasi server Anda:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Sesuaikan dengan password MySQL Anda
define('DB_NAME', 'aduan_dlhpkp');
define('APP_URL', 'http://localhost/aduan-dlhpkp-php'); // Sesuaikan dengan URL Anda
```

### 3. Setup Folder Upload

Pastikan folder `uploads/` memiliki permission yang tepat:

```bash
chmod 777 uploads/
```

### 4. Akses Website

Buka browser dan akses:
```
http://localhost/aduan-dlhpkp-php
```

## Struktur Database

### Tables

1. **categories** - Kategori pengaduan
   - Lingkungan Hidup
   - Perumahan
   - Kawasan Permukiman
   - Pertanahan

2. **statuses** - Status pengaduan
   - Menunggu Verifikasi
   - Sedang Diproses
   - Dalam Tindak Lanjut
   - Selesai
   - Ditolak

3. **complaints** - Data pengaduan masyarakat

4. **complaint_logs** - Riwayat perubahan status pengaduan

## Struktur Folder

```
aduan-dlhpkp-php/
├── assets/
│   ├── css/
│   │   └── style.css      # Stylesheet utama
│   ├── js/
│   │   └── main.js        # JavaScript utama
│   └── images/            # Folder untuk gambar statis
├── includes/
│   ├── config.php         # Konfigurasi database & functions
│   ├── header.php         # Header template
│   └── footer.php         # Footer template
├── uploads/               # Folder untuk file upload
├── index.php              # Halaman beranda
├── submit.php             # Halaman buat pengaduan
├── complaints.php         # Halaman daftar pengaduan
├── track.php              # Halaman cek status
├── view.php               # Halaman detail pengaduan
├── database.sql           # File SQL database
└── README.md              # Dokumentasi ini
```

## Fitur Keamanan

- Prepared statements untuk mencegah SQL Injection
- XSS protection dengan htmlspecialchars()
- File upload validation (tipe dan ukuran)
- Input sanitization
- CSRF protection ready

## Kustomisasi

### Mengubah Warna Tema

Edit variabel CSS di `assets/css/style.css`:

```css
:root {
    --primary-color: #059669;    /* Warna utama */
    --secondary-color: #0891b2;  /* Warna sekunder */
    --accent-color: #f59e0b;     /* Warna aksen */
}
```

### Menambah Kategori Baru

Tambahkan data ke tabel `categories` melalui phpMyAdmin atau query SQL:

```sql
INSERT INTO categories (name, slug, description, icon) 
VALUES ('Nama Kategori', 'slug-kategori', 'Deskripsi', 'fa-icon-name');
```

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)

## License

© 2024 DLHKP Kabupaten Tojo Una-Una

## Kontak

Untuk pertanyaan atau dukungan teknis:
- Email: dlhpkp@tojounauna.go.id
- Telepon: (0461) 123456
- Alamat: Jl. Poros Ampana, Kabupaten Tojo Una-Una
