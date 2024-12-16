<?php
require_once('../config.php');

// Fetch all faculties
$fakultasQuery = "SELECT idfakultas, nama, img FROM fakultas";
$fakultasResult = mysqli_query($conn, $fakultasQuery);
$fakultasData = [];

while ($row = mysqli_fetch_assoc($fakultasResult)) {
    $fakultasData[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEM PENDAFTARAN MANDIRI - Daftar Prodi</title>
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
        .card-title {
            text-align: center;
        }
        .card img {
            width: 100%;
            height: 200px; /* Set a fixed height */
            object-fit: cover; /* Ensure image covers the area, cropping if necessary */
        }
        .card {
            margin-bottom: 20px;
        }
        .card-text {
            text-align: center;
            font-weight: 500;
            color: black;
            text-decoration: none;
        }
        .card-text:hover {
            color: black;
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
        <a href="prodi.php" class="active"><i class="fas fa-university"></i> Daftar Prodi</a>
        <a href="dokumen.php"><i class="fas fa-file-alt"></i> Dokumen</a>
        <a href="daftar.php"><i class="fas fa-list"></i> Pendaftaran</a>
        <a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a>
        <a href="profil.php"><i class="fas fa-user"></i> Profil</a>
        <a href="../logoutpendaftar.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="header">
        <h2>Daftar Fakultas dan Program Studi</h2>
    </div>

    <div class="main-content">
        <div class="row">
            <?php foreach ($fakultasData as $fakultas) { ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="../admin/<?php echo htmlspecialchars($fakultas['img']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($fakultas['img']); ?>">
                    <div class="card-body">
                        <a href="#" class="card-text" data-toggle="modal" data-target="#facultyModal<?php echo htmlspecialchars($fakultas['idfakultas']); ?>"><?php echo htmlspecialchars($fakultas['nama']); ?></a>
                    </div>
                </div>
            </div>

            <!-- Faculty Modal -->
            <div class="modal fade" id="facultyModal<?php echo htmlspecialchars($fakultas['idfakultas']); ?>" tabindex="-1" role="dialog" aria-labelledby="facultyModalLabel<?php echo htmlspecialchars($fakultas['idfakultas']); ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="facultyModalLabel<?php echo htmlspecialchars($fakultas['idfakultas']); ?>"><?php echo htmlspecialchars($fakultas['nama']); ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php
                            // Fetch related programs
                            $prodiQuery = "SELECT nama_prodi FROM prodi WHERE idfakultas = " . intval($fakultas['idfakultas']);
                            $prodiResult = mysqli_query($conn, $prodiQuery);
                            while ($prodiRow = mysqli_fetch_assoc($prodiResult)) {
                                echo "<p>" . htmlspecialchars($prodiRow['nama_prodi']) . "</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
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
