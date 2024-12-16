<?php
require '../config.php';

// Ensure the 'uploads' directory exists
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}

// Function to handle file upload
function handleFileUpload($file) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('File is not an image.');</script>";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "<script>alert('Sorry, file already exists.');</script>";
        $uploadOk = 0;
    }

    // Check file size
    if ($file["size"] > 500000) {
        echo "<script>alert('Sorry, your file is too large.');</script>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script>alert('Sorry, your file was not uploaded.');</script>";
        return null;
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $targetFile;
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            return null;
        }
    }
}

// Insert Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert"])) {
    $nama = $_POST["nama"];
    $img = handleFileUpload($_FILES["img"]);

    if ($img) {
        $stmt = $conn->prepare("INSERT INTO fakultas (nama, img) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama, $img);

        if ($stmt->execute() === TRUE) {
            echo "<script>alert('Simpan data berhasil');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Update Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id = $_POST["id"];
    $nama = $_POST["nama"];
    $img = handleFileUpload($_FILES["img"]);

    if ($img) {
        $stmt = $conn->prepare("UPDATE fakultas SET nama=?, img=? WHERE idfakultas=?");
        $stmt->bind_param("ssi", $nama, $img, $id);

        if ($stmt->execute() === TRUE) {
            echo "<script>alert('Update data berhasil');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        $stmt = $conn->prepare("UPDATE fakultas SET nama=? WHERE idfakultas=?");
        $stmt->bind_param("si", $nama, $id);

        if ($stmt->execute() === TRUE) {
            echo "<script>alert('Update data berhasil');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// Delete Data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM fakultas WHERE idfakultas=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute() === TRUE) {
        echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fetch Data
$sql = "SELECT * FROM fakultas";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fakultas Dashboard</title>
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
        <h2>Data Fakultas</h2>
        <button class="btn btn-success btn-insert" data-toggle="modal" data-target="#insertModal"><i class="fas fa-plus"></i> Insert Data</button>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Image</th>
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
                                <td>" . $row['nama'] . "</td>
                                <td><img src='" . $row['img'] . "' alt='Image' width='50'></td>
                                <td>
                                    <button class='btn btn-warning btn-sm' onclick='openUpdateModal(" . json_encode($row) . ")'><i class='fas fa-edit'></i></button>
                                    <a href='fakultas.php?delete=" . $row['idfakultas'] . "' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No data available</td></tr>";
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
                    <form method="POST" action="fakultas.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="img">Image</label>
                            <input type="file" class="form-control" id="img" name="img" required>
                        </div>
                        <button type="submit" name="insert" class="btn btn-primary">Save</button>
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
                    <form method="POST" action="fakultas.php" enctype="multipart/form-data">
                        <input type="hidden" id="updateId" name="id">
                        <div class="form-group">
                            <label for="updateNama">Nama</label>
                            <input type="text" class="form-control" id="updateNama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="updateImg">Image</label>
                            <input type="file" class="form-control" id="updateImg" name="img">
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </form>
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

        // Open Update Modal with data
        function openUpdateModal(data) {
            $('#updateId').val(data.idfakultas);
            $('#updateNama').val(data.nama);
            $('#updateModal').modal('show');
        }
    </script>
</body>
</html>

