<?php
/**
 * Complaints List Page - Aduan Masyarakat DLHKP
 * Kabupaten Tojo Una-Una
 */

require_once 'includes/config.php';

$pageTitle = 'Daftar Pengaduan';
$currentPage = 'complaints';

// Get filters from URL
$filters = [
    'category' => isset($_GET['category']) ? intval($_GET['category']) : null,
    'status' => isset($_GET['status']) ? intval($_GET['status']) : null,
    'search' => isset($_GET['search']) ? sanitize($_GET['search']) : null
];

$categories = getCategories();
$statuses = getStatuses();
$complaints = getComplaints($filters);

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <div class="breadcrumb">
                <a href="<?= APP_URL ?>">Beranda</a>
                <span class="breadcrumb-separator">/</span>
                <span>Daftar Pengaduan</span>
            </div>
            <h1>Daftar Pengaduan</h1>
            <p>Lihat semua pengaduan yang masuk ke sistem kami</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="features-section">
    <div class="container">
        <!-- Search & Filter Bar -->
        <div class="search-filter-bar">
            <form method="GET" action="" class="search-filter-form">
                <div class="form-group">
                    <label class="form-label">Cari Pengaduan</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari berdasarkan judul, deskripsi, atau nama pelapor"
                           value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-control filter-auto-submit">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= ($filters['category'] ?? 0) == $category['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control filter-auto-submit">
                        <option value="">Semua Status</option>
                        <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['id'] ?>" <?= ($filters['status'] ?? 0) == $status['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-search"></i>
                        Cari
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Results Info -->
        <div class="mb-4" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <strong><?= count($complaints) ?></strong> pengaduan ditemukan
                <?php if (!empty($filters['category']) || !empty($filters['status']) || !empty($filters['search'])): ?>
                <a href="<?= APP_URL ?>/complaints.php" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; margin-left: 0.5rem;">
                    <i class="fas fa-times"></i> Reset Filter
                </a>
                <?php endif; ?>
            </div>
            <a href="<?= APP_URL ?>/submit.php" class="btn btn-success">
                <i class="fas fa-plus-circle"></i>
                Buat Pengaduan Baru
            </a>
        </div>
        
        <!-- Complaints List -->
        <?php if (count($complaints) > 0): ?>
        <div class="complaints-list">
            <?php foreach ($complaints as $complaint): ?>
            <div class="complaint-card">
                <div class="complaint-header">
                    <span class="complaint-code"><?= htmlspecialchars($complaint['complaint_code']) ?></span>
                    <span class="complaint-status status-<?= strtolower(str_replace(' ', '-', $complaint['status_name'])) ?>">
                        <?= htmlspecialchars($complaint['status_name']) ?>
                    </span>
                </div>
                <h3 class="complaint-title"><?= htmlspecialchars($complaint['complaint_title']) ?></h3>
                <p class="complaint-description"><?= nl2br(htmlspecialchars($complaint['complaint_description'])) ?></p>
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
                    <div class="complaint-meta-item">
                        <i class="fas fa-user"></i>
                        <span><?= htmlspecialchars($complaint['reporter_name']) ?></span>
                    </div>
                </div>
                <?php if ($complaint['admin_response']): ?>
                <div class="mt-3" style="background: var(--gray-50); padding: 1rem; border-radius: var(--radius); border-left: 4px solid var(--primary-color);">
                    <strong style="color: var(--primary-color);"><i class="fas fa-reply"></i> Respon Admin:</strong>
                    <p style="margin-top: 0.5rem; color: var(--gray-700);"><?= nl2br(htmlspecialchars($complaint['admin_response'])) ?></p>
                    <?php if ($complaint['responded_by']): ?>
                    <small style="color: var(--gray-500); display: block; margin-top: 0.5rem;">
                        Ditanggapi oleh <?= htmlspecialchars($complaint['responded_by']) ?> pada <?= formatDatetimeIndonesian($complaint['responded_at']) ?>
                    </small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <div class="mt-3">
                    <a href="<?= APP_URL ?>/view.php?code=<?= urlencode($complaint['complaint_code']) ?>" class="btn btn-info" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                        <i class="fas fa-eye"></i>
                        Lihat Detail
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="card" style="text-align: center; padding: 3rem;">
            <div style="font-size: 4rem; color: var(--gray-300); margin-bottom: 1rem;">
                <i class="fas fa-inbox"></i>
            </div>
            <h3 style="color: var(--gray-600); margin-bottom: 0.5rem;">Tidak Ada Pengaduan</h3>
            <p style="color: var(--gray-500); margin-bottom: 1.5rem;">
                <?php if (!empty($filters)): ?>
                Tidak ada pengaduan yang sesuai dengan filter pencarian Anda.
                <?php else: ?>
                Belum ada pengaduan yang masuk. Jadilah yang pertama untuk mengajukan pengaduan.
                <?php endif; ?>
            </p>
            <?php if (!empty($filters)): ?>
            <a href="<?= APP_URL ?>/complaints.php" class="btn btn-primary">
                <i class="fas fa-times"></i>
                Reset Filter
            </a>
            <?php else: ?>
            <a href="<?= APP_URL ?>/submit.php" class="btn btn-success">
                <i class="fas fa-plus-circle"></i>
                Buat Pengaduan Pertama
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
