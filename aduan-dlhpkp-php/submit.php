<?php
/**
 * Submit Complaint Page - Aduan Masyarakat DLHKP
 * Kabupaten Tojo Una-Una
 */

require_once 'includes/config.php';

$pageTitle = 'Buat Pengaduan';
$currentPage = 'submit';
$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'category_id' => intval($_POST['category_id']),
        'reporter_name' => sanitize($_POST['reporter_name']),
        'reporter_email' => sanitize($_POST['reporter_email']),
        'reporter_phone' => sanitize($_POST['reporter_phone']),
        'reporter_address' => sanitize($_POST['reporter_address']),
        'complaint_title' => sanitize($_POST['complaint_title']),
        'complaint_description' => sanitize($_POST['complaint_description']),
        'complaint_location' => sanitize($_POST['complaint_location']),
        'complaint_date' => sanitize($_POST['complaint_date'])
    ];
    
    // Validation
    $errors = [];
    
    if (empty($data['category_id'])) {
        $errors[] = 'Kategori pengaduan wajib dipilih';
    }
    
    if (empty($data['reporter_name'])) {
        $errors[] = 'Nama pelapor wajib diisi';
    }
    
    if (empty($data['reporter_address'])) {
        $errors[] = 'Alamat pelapor wajib diisi';
    }
    
    if (empty($data['complaint_title'])) {
        $errors[] = 'Judul pengaduan wajib diisi';
    }
    
    if (empty($data['complaint_description'])) {
        $errors[] = 'Deskripsi pengaduan wajib diisi';
    }
    
    if (empty($data['complaint_location'])) {
        $errors[] = 'Lokasi pengaduan wajib diisi';
    }
    
    if (empty($data['complaint_date'])) {
        $errors[] = 'Tanggal kejadian wajib diisi';
    }
    
    if (!empty($_POST['reporter_email']) && !filter_var($_POST['reporter_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid';
    }
    
    if (empty($errors)) {
        try {
            $complaintId = createComplaint($data);
            
            // Get the complaint code
            $conn = getDbConnection();
            $result = $conn->query("SELECT complaint_code FROM complaints WHERE id = $complaintId");
            $complaint = $result->fetch_assoc();
            
            $message = "Pengaduan berhasil dikirim! Kode pengaduan Anda: <strong>" . htmlspecialchars($complaint['complaint_code']) . "</strong>. Silakan simpan kode ini untuk melacak status pengaduan.";
            $messageType = 'success';
            
            // Clear POST data
            $_POST = array();
        } catch (Exception $e) {
            $message = 'Terjadi kesalahan saat mengirim pengaduan. Silakan coba lagi.';
            $messageType = 'error';
        }
    } else {
        $message = implode('<br>', $errors);
        $messageType = 'error';
    }
}

$categories = getCategories();

include 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <div class="breadcrumb">
                <a href="<?= APP_URL ?>">Beranda</a>
                <span class="breadcrumb-separator">/</span>
                <span>Buat Pengaduan</span>
            </div>
            <h1>Formulir Pengaduan</h1>
            <p>Silakan lengkapi formulir di bawah ini untuk mengajukan pengaduan</p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="features-section">
    <div class="container">
        <div class="card" style="max-width: 900px; margin: 0 auto;">
            <div class="card-body">
                <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?>">
                    <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                    <span><?= $message ?></span>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data" data-validate>
                    <div class="form-group">
                        <label class="form-label">
                            Kategori Pengaduan <span class="required">*</span>
                        </label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">
                                Nama Lengkap <span class="required">*</span>
                            </label>
                            <input type="text" name="reporter_name" class="form-control" 
                                   placeholder="Masukkan nama lengkap Anda"
                                   value="<?= htmlspecialchars($_POST['reporter_name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="reporter_email" class="form-control" 
                                   placeholder="contoh@email.com"
                                   value="<?= htmlspecialchars($_POST['reporter_email'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Nomor Telepon/WA</label>
                            <input type="tel" name="reporter_phone" class="form-control" 
                                   placeholder="08xxxxxxxxxx"
                                   value="<?= htmlspecialchars($_POST['reporter_phone'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Tanggal Kejadian <span class="required">*</span></label>
                            <input type="date" name="complaint_date" class="form-control" 
                                   value="<?= htmlspecialchars($_POST['complaint_date'] ?? '') ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Alamat Lengkap <span class="required">*</span>
                        </label>
                        <textarea name="reporter_address" class="form-control" rows="3" 
                                  placeholder="Masukkan alamat lengkap Anda" required><?= htmlspecialchars($_POST['reporter_address'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Judul Pengaduan <span class="required">*</span>
                        </label>
                        <input type="text" name="complaint_title" class="form-control" 
                               placeholder="Ringkasan singkat masalah Anda"
                               value="<?= htmlspecialchars($_POST['complaint_title'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Lokasi Pengaduan <span class="required">*</span>
                        </label>
                        <input type="text" name="complaint_location" class="form-control" 
                               placeholder="Contoh: Jl. Merdeka No. 10, Ampana"
                               value="<?= htmlspecialchars($_POST['complaint_location'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Deskripsi Pengaduan <span class="required">*</span>
                        </label>
                        <textarea name="complaint_description" class="form-control" rows="6" 
                                  placeholder="Jelaskan secara detail permasalahan yang Anda alami" required><?= htmlspecialchars($_POST['complaint_description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Upload Foto/Bukti (Opsional)</label>
                        <div class="file-upload">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-upload-text" id="fileUploadText">
                                Klik atau drag & drop file di sini
                            </div>
                            <div class="file-upload-hint">
                                Format: JPG, JPEG, PNG, GIF. Maksimal 5MB per file. Bisa upload multiple files.
                            </div>
                            <input type="file" name="images[]" id="images" class="hidden" multiple accept="image/*">
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Catatan Penting:</strong><br>
                            <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                                <li>Pastikan semua informasi yang diisi benar dan akurat.</li>
                                <li>Pengaduan dengan data tidak lengkap mungkin tidak dapat diproses.</li>
                                <li>Simpan kode pengaduan yang akan diberikan setelah submit untuk melacak status.</li>
                                <li>Anda akan dihubungi melalui kontak yang diberikan jika diperlukan informasi tambahan.</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <button type="submit" class="btn btn-success btn-block" style="flex: 1; min-width: 200px;">
                            <i class="fas fa-paper-plane"></i>
                            Kirim Pengaduan
                        </button>
                        <a href="<?= APP_URL ?>" class="btn btn-secondary" style="min-width: 150px; justify-content: center;">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
// Make file upload area clickable
document.querySelector('.file-upload').addEventListener('click', function(e) {
    if (e.target !== document.getElementById('images')) {
        document.getElementById('images').click();
    }
});
</script>

<?php include 'includes/footer.php'; ?>
