<?php
/**
 * Track Complaint Page - Aduan Masyarakat DLHKP
 * Kabupaten Tojo Una-Una
 */

require_once 'includes/config.php';

$pageTitle = 'Cek Status Pengaduan';
$currentPage = 'track';
$complaint = null;
$logs = [];
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['complaint_code'])) {
    $code = sanitize(trim($_POST['complaint_code']));
    $complaint = getComplaintByCode($code);
    
    if ($complaint) {
        $logs = getComplaintLogs($complaint['id']);
    } else {
        $error = 'Kode pengaduan tidak ditemukan. Silakan periksa kembali kode yang Anda masukkan.';
    }
}

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <div class="breadcrumb">
                <a href="<?= APP_URL ?>">Beranda</a>
                <span class="breadcrumb-separator">/</span>
                <span>Cek Status</span>
            </div>
            <h1>Cek Status Pengaduan</h1>
            <p>Masukkan kode pengaduan untuk melacak status pengaduan Anda</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="features-section">
    <div class="container">
        <!-- Search Form -->
        <div class="card" style="max-width: 600px; margin: 0 auto 2rem;">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Kode Pengaduan</label>
                        <div style="display: flex; gap: 0.75rem;">
                            <input type="text" name="complaint_code" class="form-control" 
                                   placeholder="Contoh: ADU-2024-001"
                                   value="<?= htmlspecialchars($_POST['complaint_code'] ?? '') ?>"
                                   style="flex: 1; text-transform: uppercase;" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                                Cek
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <?php if ($error): ?>
        <div class="alert alert-error" style="max-width: 800px; margin: 0 auto 2rem;">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $error ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($complaint): ?>
        <!-- Complaint Details -->
        <div class="card" style="max-width: 900px; margin: 0 auto 2rem;">
            <div class="card-header">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div>
                        <h2 style="font-size: 1.5rem; margin-bottom: 0.5rem;"><?= htmlspecialchars($complaint['complaint_title']) ?></h2>
                        <span class="complaint-code"><?= htmlspecialchars($complaint['complaint_code']) ?></span>
                    </div>
                    <span class="complaint-status status-<?= strtolower(str_replace(' ', '-', $complaint['status_name'])) ?>" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                        <?= htmlspecialchars($complaint['status_name']) ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <strong style="color: var(--gray-600); font-size: 0.875rem;">Kategori</strong>
                        <p style="margin-top: 0.25rem;"><i class="fas <?= $complaint['icon'] ?>" style="color: var(--primary-color);"></i> <?= htmlspecialchars($complaint['category_name']) ?></p>
                    </div>
                    <div>
                        <strong style="color: var(--gray-600); font-size: 0.875rem;">Lokasi</strong>
                        <p style="margin-top: 0.25rem;"><i class="fas fa-map-marker-alt" style="color: var(--primary-color);"></i> <?= htmlspecialchars($complaint['complaint_location']) ?></p>
                    </div>
                    <div>
                        <strong style="color: var(--gray-600); font-size: 0.875rem;">Tanggal Kejadian</strong>
                        <p style="margin-top: 0.25rem;"><i class="fas fa-calendar" style="color: var(--primary-color);"></i> <?= formatDateIndonesian($complaint['complaint_date']) ?></p>
                    </div>
                    <div>
                        <strong style="color: var(--gray-600); font-size: 0.875rem;">Tanggal Pengaduan</strong>
                        <p style="margin-top: 0.25rem;"><i class="fas fa-clock" style="color: var(--primary-color);"></i> <?= formatDatetimeIndonesian($complaint['created_at']) ?></p>
                    </div>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <strong style="color: var(--gray-600); font-size: 0.875rem;">Deskripsi Pengaduan</strong>
                    <p style="margin-top: 0.5rem; line-height: 1.7;"><?= nl2br(htmlspecialchars($complaint['complaint_description'])) ?></p>
                </div>
                
                <div style="background: var(--gray-50); padding: 1.5rem; border-radius: var(--radius);">
                    <strong style="color: var(--gray-600); font-size: 0.875rem;">Informasi Pelapor</strong>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 0.75rem;">
                        <div>
                            <span style="font-size: 0.875rem; color: var(--gray-500);">Nama</span>
                            <p style="margin-top: 0.25rem;"><?= htmlspecialchars($complaint['reporter_name']) ?></p>
                        </div>
                        <?php if ($complaint['reporter_email']): ?>
                        <div>
                            <span style="font-size: 0.875rem; color: var(--gray-500);">Email</span>
                            <p style="margin-top: 0.25rem;"><?= htmlspecialchars($complaint['reporter_email']) ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if ($complaint['reporter_phone']): ?>
                        <div>
                            <span style="font-size: 0.875rem; color: var(--gray-500);">Telepon</span>
                            <p style="margin-top: 0.25rem;"><?= htmlspecialchars($complaint['reporter_phone']) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if ($complaint['admin_response']): ?>
                <div class="mt-4" style="background: #dcfce7; padding: 1.5rem; border-radius: var(--radius); border-left: 4px solid var(--success-color);">
                    <h3 style="color: var(--success-color); margin-bottom: 0.75rem; font-size: 1.125rem;">
                        <i class="fas fa-check-circle"></i> Respon Admin
                    </h3>
                    <p style="line-height: 1.7;"><?= nl2br(htmlspecialchars($complaint['admin_response'])) ?></p>
                    <?php if ($complaint['responded_by']): ?>
                    <small style="color: var(--gray-600); display: block; margin-top: 1rem;">
                        Ditanggapi oleh <strong><?= htmlspecialchars($complaint['responded_by']) ?></strong> pada <?= formatDatetimeIndonesian($complaint['responded_at']) ?>
                    </small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="card" style="max-width: 900px; margin: 0 auto;">
            <div class="card-header">
                <h3 style="font-size: 1.25rem;"><i class="fas fa-history"></i> Riwayat Status</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php foreach ($logs as $log): ?>
                    <div class="timeline-item">
                        <div class="timeline-dot" style="background: <?= $log['status_color'] ?>;"></div>
                        <div class="timeline-content">
                            <div class="timeline-date"><?= formatDatetimeIndonesian($log['created_at']) ?></div>
                            <div class="timeline-status">
                                <span class="complaint-status status-<?= strtolower(str_replace(' ', '-', $log['status_name'])) ?>">
                                    <?= htmlspecialchars($log['status_name']) ?>
                                </span>
                            </div>
                            <?php if ($log['notes']): ?>
                            <div class="timeline-notes"><?= nl2br(htmlspecialchars($log['notes'])) ?></div>
                            <?php endif; ?>
                            <?php if ($log['changed_by']): ?>
                            <small style="color: var(--gray-500); display: block; margin-top: 0.5rem;">
                                Oleh: <?= htmlspecialchars($log['changed_by']) ?>
                            </small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= APP_URL ?>/submit.php" class="btn btn-success">
                <i class="fas fa-plus-circle"></i>
                Buat Pengaduan Baru
            </a>
            <a href="<?= APP_URL ?>" class="btn btn-secondary">
                <i class="fas fa-home"></i>
                Kembali ke Beranda
            </a>
        </div>
        <?php elseif (!$_POST): ?>
        <!-- Initial State -->
        <div class="card" style="max-width: 600px; margin: 0 auto; text-align: center; padding: 3rem;">
            <div style="font-size: 4rem; color: var(--primary-light); margin-bottom: 1.5rem;">
                <i class="fas fa-search-location"></i>
            </div>
            <h3 style="color: var(--gray-700); margin-bottom: 0.75rem;">Lacak Pengaduan Anda</h3>
            <p style="color: var(--gray-600); margin-bottom: 1.5rem;">
                Masukkan kode pengaduan yang Anda terima setelah mengirim pengaduan untuk melihat status dan riwayat perkembangan pengaduan Anda.
            </p>
            <div class="alert alert-info" style="text-align: left;">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Tip:</strong><br>
                    Kode pengaduan berbentuk <strong>ADU-YYYY-NNN</strong> (contoh: ADU-2024-001). 
                    Pastikan Anda menyimpan kode ini dengan baik setelah mengirim pengaduan.
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
