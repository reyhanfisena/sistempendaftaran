<?php
include('../config.php');
session_start();

$idpendaftar = $_SESSION['idpendaftar'];

// Function to sanitize input (prevent SQL injection)
function sanitizeInput($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($input)));
}

// Function to check if data exists in raport table for a specific semester
function isRaportExists($conn, $idpendaftar, $sem) {
    $check_sql = "SELECT COUNT(*) as count FROM raport WHERE idpendaftar = $idpendaftar AND sem = $sem";
    $result = $conn->query($check_sql);
    $count = $result->fetch_assoc()['count'];
    return $count > 0;
}

// Function to check if data exists in sertifikat table for a specific sertifikat number
function isSertifikatExists($conn, $idpendaftar, $sertifikat) {
    $check_sql = "SELECT COUNT(*) as count FROM sertifikat WHERE idpendaftar = $idpendaftar AND sertifikat = $sertifikat";
    $result = $conn->query($check_sql);
    $count = $result->fetch_assoc()['count'];
    return $count > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission

    // Update raport
    for ($i = 1; $i <= 5; $i++) {
        $nilai = sanitizeInput($conn, $_POST['rata'.$i]);
        $sem = $i;
        if (isRaportExists($conn, $idpendaftar, $sem)) {
            // Update existing entry
            $sql = "UPDATE raport SET nilai = $nilai WHERE idpendaftar = $idpendaftar AND sem = $sem";
        } else {
            // Insert new entry
            $sql = "INSERT INTO raport (idpendaftar, nilai, sem) VALUES ($idpendaftar, $nilai, $sem)";
        }
        $conn->query($sql);
    }

    // Update sertifikat
    for ($i = 1; $i <= 3; $i++) {
        $judul = sanitizeInput($conn, $_POST['sertifikat'.$i]);
        if (!empty($judul)) {
            $sertifikat = $i;
            if (isSertifikatExists($conn, $idpendaftar, $sertifikat)) {
                // Update existing entry
                $update_sql = "UPDATE sertifikat SET judul = '$judul' WHERE idpendaftar = $idpendaftar AND sertifikat = $sertifikat";
                $conn->query($update_sql);
            } else {
                // Insert new entry
                $insert_sql = "INSERT INTO sertifikat (idpendaftar, sertifikat, judul) VALUES ($idpendaftar, $sertifikat, '$judul')";
                $conn->query($insert_sql);
            }
        }
    }

    // Redirect to avoid resubmission on refresh
    header('Location: dokumen.php');
    exit();
}

// Fetch existing data for display
$raportData = [];
$raportQuery = $conn->query("SELECT * FROM raport WHERE idpendaftar = $idpendaftar");
while ($row = $raportQuery->fetch_assoc()) {
    $raportData[$row['sem']] = $row['nilai'];
}

$sertifikatData = [];
$sertifikatQuery = $conn->query("SELECT * FROM sertifikat WHERE idpendaftar = $idpendaftar");
while ($row = $sertifikatQuery->fetch_assoc()) {
    $sertifikatData[$row['sertifikat']] = $row['judul'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEM PENDAFTARAN MANDIRI - Lengkapi Dokumen</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            padding-top: 70px;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #007bff;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 15px 20px;
            text-decoration: none;
            font-weight: 500;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #0056b3;
            color: white;
        }
        .sidebar i {
            margin-right: 10px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            padding-bottom: 70px;
        }
        .alert-custom {
            background-color: #ffc107;
            color: #212529;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-control {
            border-radius: 5px;
        }
        .file-upload-btn {
            background-color: #ccc;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .file-upload-btn:hover {
            background-color: #bbb;
        }
        .btn-submit {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .header {
            background-color: #f8f9fa;
            color: #343a40;
            padding: 10px 20px;
            border-bottom: 1px solid #e7e7e7;
            font-size: 1.5rem;
            position: fixed;
            width: calc(100% - 250px);
            left: 250px;
            top: 0;
            z-index: 1000;
        }
        .header h2 {
            margin: 0;
            font-size: 1.5rem;
        }
        footer {
            background-color: #f8f9fa;
            color: #6c757d;
            padding: 10px 0;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
            border-top: 1px solid #e7e7e7;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding-top: 90px;
            }
            .sidebar {
                height: auto;
                position: relative;
                width: 100%;
            }
            .header {
                width: 100%;
                left: 0;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="text-center">
        <img src="../logo.png" class="img-fluid" alt="Logo" style="width: 50px;">
        <h5>SISTEM PENDAFTARAN MANDIRI</h5>
    </div>
    <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="prodi.php"><i class="fas fa-university"></i> Daftar Prodi</a>
    <a href="dokumen.php" class="active"><i class="fas fa-file-alt"></i> Dokumen</a>
    <a href="daftar.php"><i class="fas fa-list"></i> Pendaftaran</a>
    <a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a>
    <a href="profil.php"><i class="fas fa-user"></i> Profil</a>
    <a href="../logoutpendaftar.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="header">
    <h2>Lengkapi Dokumen</h2>
</div>

<div class="main-content">
    <div class="alert alert-custom">
        <strong>Pastikan kamu mengisi data dengan benar, sesuai ketentuan ya!</strong>
        <ul>
            <li>Kesalahan data pada dokumen berakibat penolakan</li>
            <li>Pemalsuan dokumen berakibat masuk ke daftar blacklist</li>
        </ul>
    </div>

    <form action="dokumen.php" method="post">
        <input type="hidden" name="idpendaftar" value="<?php echo $idpendaftar; ?>">
        <div class="row">
            <div class="col-md-6">
                <h4>Raport</h4>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-group d-flex align-items-center">
                        <input type="text" class="form-control" name="rata<?php echo $i; ?>" placeholder="Rata-rata Semester <?php echo $i; ?>" value="<?php echo isset($raportData[$i]) ? $raportData[$i] : ''; ?>">
                    </div>
                <?php endfor; ?>
            </div>

            <div class="col-md-6">
                <h4>Sertifikat</h4>
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="form-group d-flex align-items-center">
                        <input type="text" class="form-control" name="sertifikat<?php echo $i; ?>" placeholder="Juara dan Judul Prestasi <?php echo $i; ?>" value="<?php echo isset($sertifikatData[$i]) ? $sertifikatData[$i] : ''; ?>">
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        <button type="submit" class="btn-submit">Simpan</button>
    </form>
</div>

<footer>
    <p>&copy; 2024 Sistem Pendaftaran Mandiri UPN Veteran Jawa Timur</p>
</footer>

<!-- Font Awesome JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>
