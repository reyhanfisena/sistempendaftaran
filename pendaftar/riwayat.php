<?php
include('../config.php');
session_start();

// Periksa apakah pengguna sudah login dan idpendaftar tersimpan di session
if (!isset($_SESSION['idpendaftar'])) {
    header('Location: login.php');
    exit();
}

$idpendaftar = $_SESSION['idpendaftar'];

// Ambil data pendaftaran
$pendaftaranQuery = "
    SELECT 
        p1.idprodi AS prodi1_id, 
        p1.status AS status1, 
        p2.idprodi AS prodi2_id, 
        p2.status AS status2,
        prodi1.nama_prodi AS nama_prodi1,
        fakultas1.nama AS nama_fakultas1,
        prodi2.nama_prodi AS nama_prodi2,
        fakultas2.nama AS nama_fakultas2
    FROM 
        prodi_pil p1
    LEFT JOIN 
        prodi_pil p2 ON p1.idpendaftar = p2.idpendaftar AND p2.pilihan = 2
    LEFT JOIN 
        prodi prodi1 ON p1.idprodi = prodi1.idprodi
    LEFT JOIN 
        fakultas fakultas1 ON prodi1.idfakultas = fakultas1.idfakultas
    LEFT JOIN 
        prodi prodi2 ON p2.idprodi = prodi2.idprodi
    LEFT JOIN 
        fakultas fakultas2 ON prodi2.idfakultas = fakultas2.idfakultas
    WHERE 
        p1.idpendaftar = '$idpendaftar' AND p1.pilihan = 1
";

$pendaftaranResult = $conn->query($pendaftaranQuery);
$pendaftaranData = $pendaftaranResult->fetch_assoc();

$status = '';
if ($pendaftaranData['status1'] == 1) {
    $status = 'Diproses';
} elseif ($pendaftaranData['status1'] == 2) {
    $status = 'Diterima di Pilihan 1';
} elseif ($pendaftaranData['status1'] == 3) {
    $status = 'Diterima di Pilihan 2';
} elseif ($pendaftaranData['status1'] == 4) {
    $status = 'Ditolak';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEM PENDAFTARAN MANDIRI - Riwayat Pendaftaran</title>
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
        h2, h5 {
            font-weight: 600;
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
        .table-responsive {
            margin-top: 20px;
        }
        table {
            background-color: #fff;
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
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
        <a href="dokumen.php"><i class="fas fa-file-alt"></i> Dokumen</a>
        <a href="daftar.php"><i class="fas fa-list"></i> Pendaftaran</a>
        <a href="riwayat.php" class="active"><i class="fas fa-history"></i> Riwayat</a>
        <a href="profil.php"><i class="fas fa-user"></i> Profil</a>
        <a href="../logoutpendaftar.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="header">
        <h2>Riwayat Pendaftaran</h2>
    </div>
    <div class="main-content">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Pilihan Pertama</th>
                        <th>Pilihan Kedua</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($pendaftaranData): ?>
                        <tr>
                            <td><?php echo $pendaftaranData['nama_fakultas1'] . ' - ' . $pendaftaranData['nama_prodi1']; ?></td>
                            <td><?php echo $pendaftaranData['nama_fakultas2'] . ' - ' . $pendaftaranData['nama_prodi2']; ?></td>
                            <td><?php echo $status; ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Tidak ada data pendaftaran.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
