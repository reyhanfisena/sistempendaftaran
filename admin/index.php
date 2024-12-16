<?php
require '../config.php';
session_start();

// Mengecek apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: login.html");
    exit();
}

// Query untuk menghitung total pegawai
$queryTotalPegawai = "SELECT COUNT(*) as total FROM pengguna";
$resultTotalPegawai = $conn->query($queryTotalPegawai);
$totalPegawai = $resultTotalPegawai->fetch_assoc()['total'];

// Query untuk menghitung total fakultas
$queryTotalFakultas = "SELECT COUNT(*) as total FROM fakultas";
$resultTotalFakultas = $conn->query($queryTotalFakultas);
$totalFakultas = $resultTotalFakultas->fetch_assoc()['total'];

// Query untuk menghitung total program studi
$queryTotalProdi = "SELECT COUNT(*) as total FROM prodi"; // Menggunakan nama tabel "prodi"
$resultTotalProdi = $conn->query($queryTotalProdi);
$totalProdi = $resultTotalProdi->fetch_assoc()['total'];

$conn->close();
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
        <h2 class="text-center text-light">Admin Dashboard</h2>
        <a href="index.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="admin.php" class="nav-link"><i class="fas fa-user-shield"></i> Admin</a>
        <a href="penilai.php" class="nav-link"><i class="fas fa-clipboard-check"></i> Penilai</a>
        <a href="fakultas.php" class="nav-link"><i class="fas fa-university"></i> Fakultas</a>
        <a href="prodi.php" class="nav-link"><i class="fas fa-graduation-cap"></i> Prodi</a>
        <a href="../logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Selamat Datang, Admin</h2>
        <div class="row">
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-users card-icon"></i>
                        <h5 class="card-title">Total Pegawai</h5>
                        <p class="card-text"><?php echo $totalPegawai; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-university card-icon"></i>
                        <h5 class="card-title">Total Fakultas</h5>
                        <p class="card-text"><?php echo $totalFakultas; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-graduation-cap card-icon"></i>
                        <h5 class="card-title">Total Program Studi</h5>
                        <p class="card-text"><?php echo $totalProdi; ?></p>
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
