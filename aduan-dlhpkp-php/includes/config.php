<?php
/**
 * Database Configuration
 * Dinas Lingkungan Hidup, Perumahan, Kawasan Permukiman dan Pertanahan
 * Kabupaten Tojo Una-Una
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'aduan_dlhpkp');

// Application settings
define('APP_NAME', 'Aduan Masyarakat DLHKP');
define('APP_URL', 'http://localhost/aduan-dlhpkp-php');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Create database connection
function getDbConnection() {
    static $conn = null;
    
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            die("Koneksi database gagal: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
    }
    
    return $conn;
}

// Helper function to execute query safely
function query($sql, $params = []) {
    $conn = getDbConnection();
    
    if (empty($params)) {
        $result = $conn->query($sql);
        if ($result === false) {
            die("Query error: " . $conn->error);
        }
        return $result;
    }
    
    // Prepare statement for parameterized queries
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare error: " . $conn->error);
    }
    
    if (!empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt;
}

// Fetch all results from a statement
function fetchAll($stmt) {
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch single result from a statement
function fetchOne($stmt) {
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get all categories
function getCategories() {
    $sql = "SELECT * FROM categories ORDER BY name ASC";
    $stmt = query($sql);
    return fetchAll($stmt);
}

// Get all statuses
function getStatuses() {
    $sql = "SELECT * FROM statuses ORDER BY id ASC";
    $stmt = query($sql);
    return fetchAll($stmt);
}

// Get complaint by code
function getComplaintByCode($code) {
    $sql = "SELECT c.*, cat.name as category_name, cat.icon, s.name as status_name, s.color as status_color
            FROM complaints c
            JOIN categories cat ON c.category_id = cat.id
            JOIN statuses s ON c.status_id = s.id
            WHERE c.complaint_code = ?";
    $stmt = query($sql, [$code]);
    return fetchOne($stmt);
}

// Get all complaints with filters
function getComplaints($filters = []) {
    $sql = "SELECT c.*, cat.name as category_name, cat.icon, s.name as status_name, s.color as status_color
            FROM complaints c
            JOIN categories cat ON c.category_id = cat.id
            JOIN statuses s ON c.status_id = s.id
            WHERE 1=1";
    
    $params = [];
    
    if (!empty($filters['category'])) {
        $sql .= " AND c.category_id = ?";
        $params[] = $filters['category'];
    }
    
    if (!empty($filters['status'])) {
        $sql .= " AND c.status_id = ?";
        $params[] = $filters['status'];
    }
    
    if (!empty($filters['search'])) {
        $sql .= " AND (c.complaint_title LIKE ? OR c.complaint_description LIKE ? OR c.reporter_name LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    $sql .= " ORDER BY c.created_at DESC";
    
    if (!empty($filters['limit'])) {
        $sql .= " LIMIT ?";
        $params[] = $filters['limit'];
    }
    
    $stmt = query($sql, $params);
    return fetchAll($stmt);
}

// Create new complaint
function createComplaint($data) {
    $conn = getDbConnection();
    
    // Generate complaint code
    $year = date('Y');
    $lastCode = $conn->query("SELECT complaint_code FROM complaints WHERE complaint_code LIKE 'ADU-$year-%' ORDER BY id DESC LIMIT 1");
    
    if ($lastCode && $lastCode->num_rows > 0) {
        $row = $lastCode->fetch_assoc();
        $lastNumber = intval(substr($row['complaint_code'], -3));
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '001';
    }
    
    $complaintCode = "ADU-$year-$newNumber";
    
    // Handle image uploads
    $images = [];
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = UPLOAD_DIR;
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = $_FILES['images']['name'][$key];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                if (in_array($fileExt, ALLOWED_EXTENSIONS) && $_FILES['images']['size'][$key] <= MAX_FILE_SIZE) {
                    $newFileName = uniqid('img_') . '.' . $fileExt;
                    $uploadPath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($tmpName, $uploadPath)) {
                        $images[] = $newFileName;
                    }
                }
            }
        }
    }
    
    $imagesJson = json_encode($images);
    
    $sql = "INSERT INTO complaints 
            (complaint_code, category_id, reporter_name, reporter_email, reporter_phone, 
             reporter_address, complaint_title, complaint_description, complaint_location, 
             complaint_date, images)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = query($sql, [
        $complaintCode,
        $data['category_id'],
        $data['reporter_name'],
        $data['reporter_email'],
        $data['reporter_phone'],
        $data['reporter_address'],
        $data['complaint_title'],
        $data['complaint_description'],
        $data['complaint_location'],
        $data['complaint_date'],
        $imagesJson
    ]);
    
    // Add initial log
    $complaintId = $stmt->insert_id;
    $logSql = "INSERT INTO complaint_logs (complaint_id, status_id, notes, changed_by) VALUES (?, 1, 'Pengaduan diterima melalui website', 'System')";
    query($logSql, [$complaintId]);
    
    return $complaintId;
}

// Get complaint statistics
function getStatistics() {
    $conn = getDbConnection();
    
    $stats = [];
    
    // Total complaints
    $result = $conn->query("SELECT COUNT(*) as total FROM complaints");
    $stats['total'] = $result->fetch_assoc()['total'];
    
    // Complaints by status
    $result = $conn->query("SELECT s.name, s.color, COUNT(c.id) as count 
                           FROM statuses s 
                           LEFT JOIN complaints c ON s.id = c.status_id 
                           GROUP BY s.id, s.name, s.color");
    $stats['by_status'] = $result->fetch_all(MYSQLI_ASSOC);
    
    // Complaints by category
    $result = $conn->query("SELECT cat.name, cat.icon, COUNT(c.id) as count 
                           FROM categories cat 
                           LEFT JOIN complaints c ON cat.id = c.category_id 
                           GROUP BY cat.id, cat.name, cat.icon");
    $stats['by_category'] = $result->fetch_all(MYSQLI_ASSOC);
    
    // Recent complaints count (last 7 days)
    $result = $conn->query("SELECT COUNT(*) as count FROM complaints WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $stats['recent'] = $result->fetch_assoc()['count'];
    
    return $stats;
}

// Get complaint logs
function getComplaintLogs($complaintId) {
    $sql = "SELECT cl.*, s.name as status_name, s.color as status_color
            FROM complaint_logs cl
            JOIN statuses s ON cl.status_id = s.id
            WHERE cl.complaint_id = ?
            ORDER BY cl.created_at ASC";
    $stmt = query($sql, [$complaintId]);
    return fetchAll($stmt);
}

// Sanitize input
function sanitize($data) {
    $conn = getDbConnection();
    return $conn->real_escape_string(trim(htmlspecialchars($data)));
}

// Format date to Indonesian
function formatDateIndonesian($date) {
    $months = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $timestamp = strtotime($date);
    $day = date('d', $timestamp);
    $month = $months[(int)date('m', $timestamp)];
    $year = date('Y', $timestamp);
    
    return "$day $month $year";
}

// Format datetime to Indonesian
function formatDatetimeIndonesian($datetime) {
    $months = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $timestamp = strtotime($datetime);
    $day = date('d', $timestamp);
    $month = $months[(int)date('m', $timestamp)];
    $year = date('Y', $timestamp);
    $time = date('H:i', $timestamp);
    
    return "$day $month $year, $time WIB";
}
?>
