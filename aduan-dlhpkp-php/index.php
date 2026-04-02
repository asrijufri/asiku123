<?php
/**
 * Homepage - Aduan Masyarakat DLHKP
 * Kabupaten Tojo Una-Una
 */

require_once 'includes/config.php';

$pageTitle = 'Beranda';
$currentPage = 'home';

// Get statistics
$stats = getStatistics();
$categories = getCategories();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Sistem Pengaduan Masyarakat DLHKP</h1>
            <p>Sampaikan pengaduan Anda terkait Lingkungan Hidup, Perumahan, Kawasan Permukiman dan Pertanahan Kabupaten Tojo Una-Una dengan mudah dan cepat.</p>
            <div class="hero-buttons">
                <a href="<?= APP_URL ?>/submit.php" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i>
                    Buat Pengaduan
                </a>
                <a href="<?= APP_URL ?>/track.php" class="btn btn-secondary">
                    <i class="fas fa-search"></i>
                    Cek Status Pengaduan
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?= $stats['total'] ?></div>
                        <div class="stat-label">Total Pengaduan</div>
                    </div>
                </div>
            </div>
            
            <?php foreach ($stats['by_status'] as $status): ?>
                <?php if ($status['count'] > 0): ?>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon" style="background: linear-gradient(135deg, <?= lightenColor($status['color']) ?>, <?= $status['color'] ?>); color: white;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div class="stat-value"><?= $status['count'] ?></div>
                            <div class="stat-label"><?= $status['name'] ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header">
            <h2>Kategori Pengaduan</h2>
            <p>Pilih kategori pengaduan sesuai dengan permasalahan yang ingin Anda sampaikan</p>
        </div>
        
        <div class="features-grid">
            <?php foreach ($categories as $category): ?>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas <?= $category['icon'] ?>"></i>
                </div>
                <h3><?= htmlspecialchars($category['name']) ?></h3>
                <p><?= htmlspecialchars($category['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Recent Complaints Section -->
<section class="features-section" style="background: white;">
    <div class="container">
        <div class="section-header">
            <h2>Pengaduan Terbaru</h2>
            <p>Lihat pengaduan yang baru saja masuk ke sistem kami</p>
        </div>
        
        <?php
        $recentComplaints = getComplaints(['limit' => 5]);
        if (count($recentComplaints) > 0):
        ?>
        <div class="complaints-list">
            <?php foreach ($recentComplaints as $complaint): ?>
            <div class="complaint-card">
                <div class="complaint-header">
                    <span class="complaint-code"><?= htmlspecialchars($complaint['complaint_code']) ?></span>
                    <span class="complaint-status status-<?= strtolower(str_replace(' ', '-', $complaint['status_name'])) ?>">
                        <?= htmlspecialchars($complaint['status_name']) ?>
                    </span>
                </div>
                <h3 class="complaint-title"><?= htmlspecialchars($complaint['complaint_title']) ?></h3>
                <p class="complaint-description"><?= htmlspecialchars(substr($complaint['complaint_description'], 0, 200)) ?>...</p>
                <div class="complaint-meta">
                    <div class="complaint-meta-item">
                        <i class="fas fa-folder"></i>
                        <span><?= htmlspecialchars($complaint['category_name']) ?></span>
                    </div>
                    <div class="complaint-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?= htmlspecialchars($complaint['complaint_location']) ?></span>
                    </div>
                    <div class="complaint-meta-item">
                        <i class="fas fa-calendar"></i>
                        <span><?= formatDateIndonesian($complaint['complaint_date']) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= APP_URL ?>/complaints.php" class="btn btn-info">
                Lihat Semua Pengaduan
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Belum ada pengaduan yang masuk.
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- How It Works Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header">
            <h2>Cara Mengajukan Pengaduan</h2>
            <p>Ikuti langkah-langkah berikut untuk mengajukan pengaduan</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3>1. Isi Formulir</h3>
                <p>Lengkapi formulir pengaduan dengan data diri dan detail permasalahan yang Anda alami.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-upload"></i>
                </div>
                <h3>2. Upload Bukti</h3>
                <p>Unggah foto atau dokumen pendukung untuk memperkuat pengaduan Anda.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <h3>3. Kirim Pengaduan</h3>
                <p>Kirimkan pengaduan dan catat kode pengaduan untuk melacak statusnya.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>4. Pantau Progress</h3>
                <p>Pantau perkembangan pengaduan Anda secara real-time melalui website.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<?php
// Helper function to lighten color for gradient
function lightenColor($hex, $percent = 20) {
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    $r = min(255, $r + (255 - $r) * $percent / 100);
    $g = min(255, $g + (255 - $g) * $percent / 100);
    $b = min(255, $b + (255 - $b) * $percent / 100);
    
    return '#' . sprintf('%02x%02x%02x', $r, $g, $b);
}
?>
