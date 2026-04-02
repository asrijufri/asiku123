<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Aduan Masyarakat DLHKP' ?> - Kabupaten Tojo Una-Una</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <a href="<?= APP_URL ?>" class="navbar-brand">
                    <div class="brand-logo">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="brand-text">
                        <span class="brand-title">DLHKP</span>
                        <span class="brand-subtitle">Kabupaten Tojo Una-Una</span>
                    </div>
                </a>
                
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
                
                <ul class="navbar-menu" id="navbarMenu">
                    <li><a href="<?= APP_URL ?>" class="<?= ($currentPage ?? '') == 'home' ? 'active' : '' ?>">Beranda</a></li>
                    <li><a href="<?= APP_URL ?>/complaints.php" class="<?= ($currentPage ?? '') == 'complaints' ? 'active' : '' ?>">Daftar Pengaduan</a></li>
                    <li><a href="<?= APP_URL ?>/submit.php" class="<?= ($currentPage ?? '') == 'submit' ? 'active' : '' ?>">Buat Pengaduan</a></li>
                    <li><a href="<?= APP_URL ?>/track.php" class="<?= ($currentPage ?? '') == 'track' ? 'active' : '' ?>">Cek Status</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
