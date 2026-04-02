-- Database configuration for Aduan Masyarakat DLHKP Kabupaten Tojo Una-Una

CREATE DATABASE IF NOT EXISTS aduan_dlhpkp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE aduan_dlhpkp;

-- Table untuk kategori pengaduan
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50) DEFAULT 'fa-folder',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table untuk status pengaduan
CREATE TABLE IF NOT EXISTS statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE,
    color VARCHAR(20) DEFAULT '#6c757d',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table untuk pengaduan masyarakat
CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_code VARCHAR(20) NOT NULL UNIQUE,
    category_id INT NOT NULL,
    status_id INT NOT NULL DEFAULT 1,
    reporter_name VARCHAR(100) NOT NULL,
    reporter_email VARCHAR(100),
    reporter_phone VARCHAR(20),
    reporter_address TEXT NOT NULL,
    complaint_title VARCHAR(200) NOT NULL,
    complaint_description TEXT NOT NULL,
    complaint_location VARCHAR(255) NOT NULL,
    complaint_date DATE NOT NULL,
    images JSON,
    admin_response TEXT,
    responded_by VARCHAR(100),
    responded_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (status_id) REFERENCES statuses(id) ON DELETE CASCADE,
    INDEX idx_complaint_code (complaint_code),
    INDEX idx_status (status_id),
    INDEX idx_category (category_id),
    INDEX idx_created_at (created_at)
);

-- Table untuk tracking status pengaduan
CREATE TABLE IF NOT EXISTS complaint_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complaint_id INT NOT NULL,
    status_id INT NOT NULL,
    notes TEXT,
    changed_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (complaint_id) REFERENCES complaints(id) ON DELETE CASCADE,
    FOREIGN KEY (status_id) REFERENCES statuses(id) ON DELETE CASCADE
);

-- Insert data awal untuk kategori
INSERT INTO categories (name, slug, description, icon) VALUES
('Lingkungan Hidup', 'lingkungan-hidup', 'Pengaduan terkait pencemaran lingkungan, sampah, dan kerusakan ekosistem', 'fa-leaf'),
('Perumahan', 'perumahan', 'Pengaduan terkait masalah perumahan dan hunian', 'fa-home'),
('Kawasan Permukiman', 'kawasan-permukiman', 'Pengaduan terkait infrastruktur permukiman', 'fa-building'),
('Pertanahan', 'pertanahan', 'Pengaduan terkait masalah pertanahan dan sertifikat', 'fa-map-marked-alt');

-- Insert data awal untuk status
INSERT INTO statuses (name, slug, color) VALUES
('Menunggu Verifikasi', 'menunggu-verifikasi', '#ffc107'),
('Sedang Diproses', 'sedang-diproses', '#17a2b8'),
('Dalam Tindak Lanjut', 'dalam-tindak-lanjut', '#007bff'),
('Selesai', 'selesai', '#28a745'),
('Ditolak', 'ditolak', '#dc3545');

-- Insert beberapa data contoh pengaduan
INSERT INTO complaints (complaint_code, category_id, status_id, reporter_name, reporter_email, reporter_phone, reporter_address, complaint_title, complaint_description, complaint_location, complaint_date, images) VALUES
('ADU-2024-001', 1, 4, 'Ahmad Santoso', 'ahmad@email.com', '081234567890', 'Jl. Merdeka No. 10, Ampana', 'Pembuangan Sampah Ilegal', 'Terdapat pembuangan sampah ilegal di bantaran sungai yang menyebabkan pencemaran air dan bau tidak sedap. Mohon segera ditindaklanjuti.', 'Bantaran Sungai Ampana', '2024-01-15', '["sample1.jpg"]'),
('ADU-2024-002', 2, 3, 'Siti Nurhaliza', 'siti@email.com', '081234567891', 'Jl. Diponegoro No. 25, Ampana', 'Rusaknya Jalan Perumahan', 'Jalan di komplek perumahan sudah rusak parah dan berlubang, membahayakan pengguna jalan terutama saat hujan.', 'Komplek Perumahan Griya Asri', '2024-01-18', '["sample2.jpg","sample3.jpg"]'),
('ADU-2024-003', 3, 2, 'Budi Hartono', 'budi@email.com', '081234567892', 'Jl. Ahmad Yani No. 5, Tojo', 'Drainase Tersumbat', 'Drainase di kawasan permukiman tersumbat sehingga menyebabkan banjir setiap kali hujan deras.', 'Kawasan Permukiman Tojo Barat', '2024-01-20', '[]'),
('ADU-2024-004', 4, 1, 'Dewi Lestari', 'dewi@email.com', '081234567893', 'Jl. Sudirman No. 15, Una-Una', 'Masalah Sertifikat Tanah', 'Saya mengalami kesulitan dalam proses pembuatan sertifikat tanah warisan. Mohon bantuannya.', 'Kelurahan Una-Una', '2024-01-22', '[]');

-- Insert log untuk data contoh
INSERT INTO complaint_logs (complaint_id, status_id, notes, changed_by) VALUES
(1, 1, 'Pengaduan diterima melalui website', 'System'),
(1, 2, 'Verifikasi awal selesai, diteruskan ke bidang terkait', 'Admin'),
(1, 3, 'Tim lapangan sedang melakukan pemeriksaan', 'Admin'),
(1, 4, 'Masalah telah diselesaikan, sampah telah diangkut', 'Admin'),
(2, 1, 'Pengaduan diterima melalui website', 'System'),
(2, 2, 'Verifikasi awal selesai', 'Admin'),
(2, 3, 'Dalam proses perbaikan oleh dinas terkait', 'Admin'),
(3, 1, 'Pengaduan diterima melalui website', 'System'),
(3, 2, 'Sedang dijadwalkan untuk pembersihan', 'Admin'),
(4, 1, 'Pengaduan diterima melalui website', 'System');
