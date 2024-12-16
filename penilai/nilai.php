<?php
include('../config.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../login.php");
    exit();
}

// Ambil id pengguna dari session
$id_pengguna = $_SESSION['idpengguna'];

// Periksa apakah ada data yang dikirim melalui POST untuk mengupdate status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idpendaftar']) && isset($_POST['status'])) {
    $idpendaftar = $_POST['idpendaftar'];
    $status = $_POST['status'];

    // Update status pendaftar
    $update_sql = "UPDATE prodi_pil SET status = ? WHERE idpendaftar = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('ii', $status, $idpendaftar);
    $stmt->execute();
}

// Query untuk mengambil data pendaftar yang sesuai dengan kriteria
$sql = "
    SELECT 
        pendaftar.idpendaftar,
        pendaftar.nama,
        raport1.nilai AS nilai_sem1,
        raport2.nilai AS nilai_sem2,
        raport3.nilai AS nilai_sem3,
        raport4.nilai AS nilai_sem4,
        raport5.nilai AS nilai_sem5,
        s1.judul AS sertifikat1,
        s2.judul AS sertifikat2,
        s3.judul AS sertifikat3,
        prodi1.nama_prodi AS prodi_pilihan1,
        prodi2.nama_prodi AS prodi_pilihan2,
        prodi_pil.status
    FROM pendaftar
    LEFT JOIN raport raport1 ON raport1.idpendaftar = pendaftar.idpendaftar AND raport1.sem = 1
    LEFT JOIN raport raport2 ON raport2.idpendaftar = pendaftar.idpendaftar AND raport2.sem = 2
    LEFT JOIN raport raport3 ON raport3.idpendaftar = pendaftar.idpendaftar AND raport3.sem = 3
    LEFT JOIN raport raport4 ON raport4.idpendaftar = pendaftar.idpendaftar AND raport4.sem = 4
    LEFT JOIN raport raport5 ON raport5.idpendaftar = pendaftar.idpendaftar AND raport5.sem = 5
    LEFT JOIN sertifikat s1 ON s1.idpendaftar = pendaftar.idpendaftar AND s1.sertifikat = 1
    LEFT JOIN sertifikat s2 ON s2.idpendaftar = pendaftar.idpendaftar AND s2.sertifikat = 2
    LEFT JOIN sertifikat s3 ON s3.idpendaftar = pendaftar.idpendaftar AND s3.sertifikat = 3
    LEFT JOIN prodi_pil pp1 ON pp1.idpendaftar = pendaftar.idpendaftar AND pp1.pilihan = 1
    LEFT JOIN prodi prodi1 ON prodi1.idprodi = pp1.idprodi
    LEFT JOIN prodi_pil pp2 ON pp2.idpendaftar = pendaftar.idpendaftar AND pp2.pilihan = 2
    LEFT JOIN prodi prodi2 ON prodi2.idprodi = pp2.idprodi
    LEFT JOIN prodi_pil ON prodi_pil.idpendaftar = pendaftar.idpendaftar
    WHERE prodi1.id_penilai = $id_pengguna OR prodi2.id_penilai = $id_pengguna
    GROUP BY pendaftar.idpendaftar
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilai Pendaftar</title>
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
    <nav class="navbar navbar-dark bg-dark d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>

    <div class="sidebar collapse d-md-block" id="sidebarMenu">
        <h2 class="text-center text-light">Penilai Dashboard</h2>
        <a href="index.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="nilai.php" class="nav-link"><i class="fas fa-user-shield"></i> Nilai</a>
        <a href="../logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h2>Nilai Pendaftar</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Nilai Sem 1</th>
                    <th>Nilai Sem 2</th>
                    <th>Nilai Sem 3</th>
                    <th>Nilai Sem 4</th>
                    <th>Nilai Sem 5</th>
                    <th>Sertifikat 1</th>
                    <th>Sertifikat 2</th>
                    <th>Sertifikat 3</th>
                    <th>Prodi Pilihan 1</th>
                    <th>Prodi Pilihan 2</th>
                    <th>Status</th>
                    <th>Simpan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    $status = '';
                    switch ($row['status']) {
                        case 1:
                            $status = 'Proses';
                            break;
                        case 2:
                            $status = 'Diterima Pilihan 1';
                            break;
                        case 3:
                            $status = 'Diterima Pilihan 2';
                            break;
                        case 4:
                            $status = 'Ditolak';
                            break;
                        default:
                            $status = 'Tidak Valid';
                    }
                    echo "<tr>
                        <td>" . htmlspecialchars($row['nama']) . "</td>
                        <td>" . htmlspecialchars($row['nilai_sem1']) . "</td>
                        <td>" . htmlspecialchars($row['nilai_sem2']) . "</td>
                        <td>" . htmlspecialchars($row['nilai_sem3']) . "</td>
                        <td>" . htmlspecialchars($row['nilai_sem4']) . "</td>
                        <td>" . htmlspecialchars($row['nilai_sem5']) . "</td>
                        <td>" . htmlspecialchars($row['sertifikat1']) . "</td>
                        <td>" . htmlspecialchars($row['sertifikat2']) . "</td>
                        <td>" . htmlspecialchars($row['sertifikat3']) . "</td>
                        <td>" . htmlspecialchars($row['prodi_pilihan1']) . "</td>
                        <td>" . htmlspecialchars($row['prodi_pilihan2']) . "</td>
                        <td>
                            <form method='POST' action=''>
                                <input type='hidden' name='idpendaftar' value='" . $row['idpendaftar'] . "' />
                                <select name='status' class='form-control'>
                                    <option value='0'" . ($row['status'] == 0 ? ' selected' : '') . ">Belum Dinilai</option>
                                    <option value='2'" . ($row['status'] == 2 ? ' selected' : '') . ">Diterima Pilihan 1</option>
                                    <option value='3'" . ($row['status'] == 3 ? ' selected' : '') . ">Diterima Pilihan 2</option>
                                    <option value='4'" . ($row['status'] == 4 ? ' selected' : '') . ">Ditolak</option>
                                </select>
                        </td>
                        <td><button type='submit' class='btn btn-primary'>Simpan</button></form></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.navbar-toggler').click(function(){
                $('.sidebar').toggleClass('show');
            });
        });
    </script>
</body>
</html>
