<?php
require '../config.php';

// Insert Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert"])) {
    $idfakultas = $_POST["idfakultas"];
    $nama_prodi = $_POST["nama_prodi"];
    $id_penilai = $_POST["id_penilai"];

    $stmt = $conn->prepare("INSERT INTO prodi (idfakultas, nama_prodi, id_penilai) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $idfakultas, $nama_prodi, $id_penilai);
    
    if ($stmt->execute() === TRUE) {
        echo "<script>alert('Simpan data berhasil');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Update Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id = $_POST["id"];
    $idfakultas = $_POST["idfakultas"];
    $nama_prodi = $_POST["nama_prodi"];
    $id_penilai = $_POST["id_penilai"];

    $stmt = $conn->prepare("UPDATE prodi SET idfakultas=?, nama_prodi=?, id_penilai=? WHERE idprodi=?");
    $stmt->bind_param("isii", $idfakultas, $nama_prodi, $id_penilai, $id);
    
    if ($stmt->execute() === TRUE) {
        echo "<script>alert('Update data berhasil');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Delete Data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM prodi WHERE idprodi=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute() === TRUE) {
        echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fetch Data
$sql = "SELECT prodi.*, fakultas.nama AS fakultas_nama, pengguna.username AS penilai_nama 
        FROM prodi 
        JOIN fakultas ON prodi.idfakultas = fakultas.idfakultas 
        JOIN pengguna ON prodi.id_penilai = pengguna.idpengguna 
        WHERE pengguna.role = 2";
$result = $conn->query($sql);

// Fetch fakultas data for dropdown
$fakultas_sql = "SELECT * FROM fakultas";
$fakultas_result = $conn->query($fakultas_sql);

// Fetch pengguna data for dropdown
$pengguna_sql = "SELECT * FROM pengguna WHERE role = 2";
$pengguna_result = $conn->query($pengguna_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .table-responsive {
            margin-top: 20px;
        }
        .btn-insert {
            margin-bottom: 10px;
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
        <h2>Data Prodi</h2>
        <button class="btn btn-success btn-insert" data-toggle="modal" data-target="#insertModal"><i class="fas fa-plus"></i> Insert Data</button>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Program Studi</th>
                        <th>Fakultas</th>
                        <th>Penilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . $no++ . "</td>
                                <td>" . $row['nama_prodi'] . "</td>
                                <td>" . $row['fakultas_nama'] . "</td>
                                <td>" . $row['penilai_nama'] . "</td>
                                <td>
                                    <button class='btn btn-warning btn-sm' onclick='openUpdateModal(" . json_encode($row) . ")'><i class='fas fa-edit'></i></button>
                                    <a href='prodi.php?delete=" . $row['idprodi'] . "' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No data available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Insert Data Modal -->
    <div class="modal fade" id="insertModal" tabindex="-1" aria-labelledby="insertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="insertModalLabel">Insert Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="prodi.php">
                        <div class="form-group">
                            <label for="idfakultas">Fakultas</label>
                            <select class="form-control" id="idfakultas" name="idfakultas" required>
                                <?php
                                if ($fakultas_result->num_rows > 0) {
                                    while ($row = $fakultas_result->fetch_assoc()) {
                                        echo "<option value='" . $row['idfakultas'] . "'>" . $row['nama'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Prodi</label>
                            <input type="text" class="form-control" id="nama" name="nama_prodi" required>
                        </div>
                        <div class="form-group">
                            <label for="id_penilai">Penilai</label>
                            <select class="form-control" id="id_penilai" name="id_penilai" required>
                                <?php
                                if ($pengguna_result->num_rows > 0) {
                                    while ($row = $pengguna_result->fetch_assoc()) {
                                        echo "<option value='" . $row['idpengguna'] . "'>" . $row['username'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="insert">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Data Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="prodi.php">
                        <input type="hidden" id="update_id" name="id">
                        <div class="form-group">
                            <label for="update_idfakultas">Fakultas</label>
                            <select class="form-control" id="update_idfakultas" name="idfakultas" required>
                                <?php
                                // Reset the result pointer and fetch fakultas data again for update modal
                                $fakultas_result->data_seek(0);
                                if ($fakultas_result->num_rows > 0) {
                                    while ($row = $fakultas_result->fetch_assoc()) {
                                        echo "<option value='" . $row['idfakultas'] . "'>" . $row['nama'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="update_nama">Nama Prodi</label>
                            <input type="text" class="form-control" id="update_nama" name="nama_prodi" required>
                        </div>
                        <div class="form-group">
                            <label for="update_id_penilai">Penilai</label>
                            <select class="form-control" id="update_id_penilai" name="id_penilai" required>
                                <?php
                                // Reset the result pointer and fetch pengguna data again for update modal
                                $pengguna_result->data_seek(0);
                                if ($pengguna_result->num_rows > 0) {
                                    while ($row = $pengguna_result->fetch_assoc()) {
                                        echo "<option value='" . $row['idpengguna'] . "'>" . $row['username'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="update">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function openUpdateModal(data) {
            $('#update_id').val(data.idprodi);
            $('#update_idfakultas').val(data.idfakultas);
            $('#update_nama').val(data.nama_prodi);
            $('#update_id_penilai').val(data.id_penilai);
            $('#updateModal').modal('show');
        }
    </script>
</body>
</html>