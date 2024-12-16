<?php
require '../config.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../login.php");
    exit();
}
// Fungsi untuk mendapatkan jumlah pendaftar berdasarkan id_penilai dan pilihan prodi
function hitungJumlahPendaftar($conn, $id_penilai, $pilihan = null) {
    // Query dasar untuk menghitung jumlah pendaftar
    $sql = "
        SELECT prodi.id_penilai, COUNT(DISTINCT prodi_pil.idpendaftar) AS jumlah_pendaftar
        FROM prodi
        JOIN prodi_pil ON prodi.idprodi = prodi_pil.idprodi
        WHERE prodi.id_penilai = ?";
    
    // Jika pilihan prodi ditentukan, tambahkan kondisi WHERE untuk pilihan
    if ($pilihan !== null) {
        $sql .= " AND prodi_pil.pilihan = ?";
    }
    
    $sql .= " GROUP BY prodi.id_penilai";
    
    // Persiapan statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameter id_penilai
    if ($pilihan !== null) {
        $stmt->bind_param('si', $id_penilai, $pilihan);
    } else {
        $stmt->bind_param('s', $id_penilai);
    }
    
    // Eksekusi query
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Ambil jumlah pendaftar dari hasil query
    if ($result->num_rows > 0) {
        $jumlah_pendaftar = $result->fetch_assoc()['jumlah_pendaftar'];
    } else {
        $jumlah_pendaftar = 0; // Jika tidak ada hasil, kembalikan 0
    }
    
    // Tutup statement
    $stmt->close();
    
    return $jumlah_pendaftar;
}

// Ambil id_penilai dari session
$id_penilai = $_SESSION['idpengguna'];

// Hitung total pendaftar
$total_pendaftar = hitungJumlahPendaftar($conn, $id_penilai);

// Hitung total pendaftar untuk pilihan 1
$total_pilihan1 = hitungJumlahPendaftar($conn, $id_penilai, 1);

// Hitung total pendaftar untuk pilihan 2
$total_pilihan2 = hitungJumlahPendaftar($conn, $id_penilai, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            min-height: 100vh;
            padding-top: 20px;
            position: fixed;
            transition: all 0.3s;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 250px;
            transition: margin-left 0.3s;
        }
        .card-icon {
            font-size: 2em;
            color: #ff6f61;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                position: static;
                display: none;
            }
            .sidebar.show {
                display: block;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    <!-- Sidebar -->
    <div class="sidebar collapse d-md-block" id="sidebarMenu">
        <h2 class="text-center text-light">Penilai Dashboard</h2>
        <a href="index.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="nilai.php" class="nav-link"><i class="fas fa-user-shield"></i> Nilai</a>
        <a href="../logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Selamat Datang, Penilai</h2>
        <div class="row">
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-users card-icon"></i>
                        <h5 class="card-title">Total Pendaftar</h5>
                        <p class="card-text"><?php echo $total_pendaftar; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-graduation-cap card-icon"></i>
                        <h5 class="card-title">Total Pendaftar Pilihan 1</h5>
                        <p class="card-text"><?php echo $total_pilihan1; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-graduation-cap card-icon"></i>
                        <h5 class="card-title">Total Pendaftar Pilihan 2</h5>
                        <p class="card-text"><?php echo $total_pilihan2; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Toggle sidebar for small screens
        $(document).ready(function(){
            $('.navbar-toggler').click(function(){
                $('#sidebarMenu').toggleClass('show');
            });
        });
    </script>
</body>
</html>