<?php
include('../config.php');
session_start();

// Ambil idpendaftar dari sesi login
$idpendaftar = $_SESSION['idpendaftar'];

// Ambil data pengguna dari tabel pendaftar
$pendaftarQuery = "SELECT * FROM pendaftar WHERE idpendaftar = $idpendaftar";
$pendaftarResult = $conn->query($pendaftarQuery);
$pendaftarData = $pendaftarResult->fetch_assoc();

// Ambil data raport
$raportQuery = "SELECT DISTINCT sem, nilai FROM raport WHERE idpendaftar = $idpendaftar ORDER BY sem ASC";
$raportResult = $conn->query($raportQuery);
$raportData = $raportResult->fetch_all(MYSQLI_ASSOC);

// Ambil data sertifikat
$sertifikatQuery = "SELECT * FROM sertifikat WHERE idpendaftar = $idpendaftar";
$sertifikatResult = $conn->query($sertifikatQuery);
$sertifikatData = $sertifikatResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEM PENDAFTARAN MANDIRI</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            padding-top: 70px; /* Adjusted for header space */
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
            padding-bottom: 70px; /* Adjusted for footer space */
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
        .data-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .data-section .card {
            width: 48%;
        }
        .profile-img {
            width: 100px;
            height: auto;
            float: right;
        }
        h2, h5 {
            font-weight: 600;
        }
        p {
            font-weight: 400;
            margin-bottom: 5px;
        }
        strong {
            font-weight: 500;
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
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding-top: 90px; /* Adjusted for header space */
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
            .data-section {
                flex-direction: column;
            }
            .data-section .card {
                width: 100%;
                margin-bottom: 20px;
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
        <a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="prodi.php"><i class="fas fa-university"></i> Daftar Prodi</a>
        <a href="dokumen.php"><i class="fas fa-file-alt"></i> Dokumen</a>
        <a href="daftar.php"><i class="fas fa-list"></i> Pendaftaran</a>
        <a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a>
        <a href="profil.php"><i class="fas fa-user"></i> Profil</a>
        <a href="../logoutpendaftar.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="header">
        <h2>Dashboard</h2>
    </div>
    <div class="main-content">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Data Diri</h5>
                <img src="<?php echo $pendaftarData['img'] ? '../upload_files/' . $pendaftarData['img'] : 'path/to/your/default/profile.jpg'; ?>" class="profile-img" alt="Profile Picture">
                <p><strong>Nama:</strong> <?php echo $pendaftarData['nama']; ?></p>
                <p><strong>NISN:</strong> <?php echo $pendaftarData['nisn']; ?></p>
                <p><strong>Asal Sekolah:</strong> <?php echo $pendaftarData['asalsekolah']; ?></p>
                <p><strong>Alamat:</strong> <?php echo $pendaftarData['alamat']; ?></p>
            </div>
        </div>
        <div class="data-section">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Nilai Raport</h5>
                    <?php foreach ($raportData as $raport): ?>
                        <p>Semester <?php echo $raport['sem']; ?>: <strong><?php echo $raport['nilai']; ?></strong></p>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Prestasi</h5>
                    <?php foreach ($sertifikatData as $sertifikat): ?>
                        <p><?php echo $sertifikat['judul']; ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Sistem Pendaftaran Mandiri UPN Veteran Jawa Timur</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>