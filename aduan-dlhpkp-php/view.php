<?php
/**
 * View Complaint Detail Page - Aduan Masyarakat DLHKP
 * Kabupaten Tojo Una-Una
 */

require_once 'includes/config.php';

$pageTitle = 'Detail Pengaduan';
$currentPage = 'complaints';
$complaint = null;
$logs = [];
$error = '';

// Get complaint code from URL
if (isset($_GET['code'])) {
    $code = sanitize(trim($_GET['code']));
    $complaint = getComplaintByCode($code);
    
    if ($complaint) {
        $logs = getComplaintLogs($complaint['id']);
    } else {
        $error = 'Pengaduan tidak ditemukan.';
    }
} else {
    header('Location: ' . APP_URL . '/complaints.php');
    exit;
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
                <a href="<?= APP_URL ?>/complaints.php">Daftar Pengaduan</a>
                <span class="breadcrumb-separator">/</span>
                <span>Detail</span>
            </div>
            <h1>Detail Pengaduan</h1>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="features-section">
    <div class="container">
        <?php if ($error): ?>
        <div class="alert alert-error" style="max-width: 800px; margin: 0 auto 2rem;">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $error ?></span>
        </div>
        <div class="text-center">
            <a href="<?= APP_URL ?>/complaints.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Daftar Pengaduan
            </a>
        </div>
        <?php elseif ($complaint): ?>
        
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
                
                <?php
                // Display images if available
                $images = json_decode($complaint['images'], true);
                if (!empty($images)):
                ?>
                <div style="margin-bottom: 1.5rem;">
                    <strong style="color: var(--gray-600); font-size: 0.875rem;">Foto/Bukti</strong>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-top: 0.75rem;">
                        <?php foreach ($images as $image): ?>
                        <div style="border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow);">
                            <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($image) ?>" 
                                 alt="Bukti pengaduan" 
                                 style="width: 100%; height: 150px; object-fit: cover; cursor: pointer;"
                                 onclick="openImageModal('<?= APP_URL ?>/uploads/<?= htmlspecialchars($image) ?>')">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
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
                        <div>
                            <span style="font-size: 0.875rem; color: var(--gray-500);">Alamat</span>
                            <p style="margin-top: 0.25rem;"><?= nl2br(htmlspecialchars($complaint['reporter_address'])) ?></p>
                        </div>
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
        <div class="card" style="max-width: 900px; margin: 0 auto 2rem;">
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
        
        <div class="text-center">
            <a href="<?= APP_URL ?>/complaints.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Daftar Pengaduan
            </a>
            <a href="<?= APP_URL ?>/track.php" class="btn btn-secondary">
                <i class="fas fa-search"></i>
                Cek Status Pengaduan Lain
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Image Modal -->
<div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.9); z-index: 9999; align-items: center; justify-content: center;" onclick="closeImageModal()">
    <img id="modalImage" src="" alt="Full size image" style="max-width: 90%; max-height: 90%; object-fit: contain;">
    <button onclick="closeImageModal()" style="position: absolute; top: 20px; right: 30px; background: transparent; color: white; font-size: 2rem; border: none; cursor: pointer;">&times;</button>
</div>

<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').style.display = 'flex';
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>

<?php include 'includes/footer.php'; ?>
