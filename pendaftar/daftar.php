<?php
include('../config.php');
session_start();

// Periksa apakah pengguna sudah login dan idpendaftar tersimpan di session
if (!isset($_SESSION['idpendaftar'])) {
    header('Location: login.php');
    exit();
}

$idpendaftar = $_SESSION['idpendaftar'];

// Periksa apakah pengguna sudah mendaftar
$cekPendaftaranQuery = "SELECT * FROM prodi_pil WHERE idpendaftar = '$idpendaftar'";
$cekPendaftaranResult = $conn->query($cekPendaftaranQuery);
$sudahMendaftar = $cekPendaftaranResult->num_rows > 0;

// Ambil data prodi dan nama fakultas
$prodiQuery = "SELECT p.idprodi, p.nama_prodi, f.nama
               FROM prodi p 
               JOIN fakultas f ON p.idfakultas = f.idfakultas";
$prodiResult = $conn->query($prodiQuery);
$prodiData = [];

if ($prodiResult->num_rows > 0) {
    while ($row = $prodiResult->fetch_assoc()) {
        $prodiData[] = $row;
    }
}

$daftarBerhasil = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$sudahMendaftar) {
        $pilihan1 = $_POST['prodi1'];
        $pilihan2 = $_POST['prodi2'];

        // Simpan pilihan pertama
        $insertPilihan1 = "INSERT INTO prodi_pil (idpendaftar, idprodi, pilihan, status) VALUES ('$idpendaftar', '$pilihan1', 1, 1)";
        $conn->query($insertPilihan1);

        // Simpan pilihan kedua
        $insertPilihan2 = "INSERT INTO prodi_pil (idpendaftar, idprodi, pilihan, status) VALUES ('$idpendaftar', '$pilihan2', 2, 1)";
        $conn->query($insertPilihan2);

        $daftarBerhasil = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEM PENDAFTARAN MANDIRI - Daftar</title>
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
        .form-group {
            margin-bottom: 1.5rem;
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
    <script>
        function confirmSubmission() {
            return confirm("Apakah Anda yakin dengan pilihan Anda?");
        }

        function showSuccessMessage() {
            alert("Pendaftaran berhasil!");
            window.location.href = "riwayat.php";
        }
    </script>
</head>
<body>
    <?php if ($daftarBerhasil): ?>
        <script>
            showSuccessMessage();
        </script>
    <?php endif; ?>

    <div class="sidebar">
        <div class="text-center">
            <img src="../logo.png" class="img-fluid" alt="Logo" style="width: 50px;">
            <h5>SISTEM PENDAFTARAN MANDIRI</h5>
        </div>
        <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="prodi.php"><i class="fas fa-university"></i> Daftar Prodi</a>
        <a href="dokumen.php"><i class="fas fa-file-alt"></i> Dokumen</a>
        <a href="daftar.php" class="active"><i class="fas fa-list"></i> Pendaftaran</a>
        <a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a>
        <a href="profil.php"><i class="fas fa-user"></i> Profil</a>
        <a href="../logoutpendaftar.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="header">
        <h2>Pendaftaran</h2>
    </div>
    <div class="main-content">
        <?php if ($sudahMendaftar): ?>
            <div class="alert alert-info">
                Anda sudah melakukan pendaftaran.
            </div>
        <?php else: ?>
            <form action="daftar.php" method="post" onsubmit="return confirmSubmission();">
                <div class="form-group">
                    <label for="prodi1">Pilihan Pertama:</label>
                    <select class="form-control" id="prodi1" name="prodi1" required>
                        <option value="">Pilih Fakultas dan Program Studi</option>
                        <?php foreach($prodiData as $data): ?>
                            <option value="<?php echo $data['idprodi']; ?>">
                                <?php echo $data['nama'] . ' - ' . $data['nama_prodi']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="prodi2">Pilihan Kedua:</label>
                    <select class="form-control" id="prodi2" name="prodi2" required>
                        <option value="">Pilih Fakultas dan Program Studi</option>
                        <?php foreach($prodiData as $data): ?>
                            <option value="<?php echo $data['idprodi']; ?>">
                                <?php echo $data['nama'] . ' - ' . $data['nama_prodi']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Daftar</button>
            </form>
        <?php endif; ?>
    </div>
    <footer>
        <p>&copy; 2024 Sistem Pendaftaran Mandiri UPN Veteran Jawa Timur</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
