<?php
require '../config.php';

// Insert Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $stmt = $conn->prepare("INSERT INTO pengguna (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $username, $password, $role);
    
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
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $stmt = $conn->prepare("UPDATE pengguna SET username=?, password=?, role=? WHERE idpengguna=?");
    $stmt->bind_param("ssii", $username, $password, $role, $id);
    
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
    $stmt = $conn->prepare("DELETE FROM pengguna WHERE idpengguna=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute() === TRUE) {
        echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fetch Data
$sql = "SELECT * FROM pengguna WHERE role = 1";
$result = $conn->query($sql);
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
        <h2>Data Admin</h2>
        <button class="btn btn-success btn-insert" data-toggle="modal" data-target="#insertModal"><i class="fas fa-plus"></i> Insert Data</button>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Role</th>
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
                                <td>" . $row['username'] . "</td>
                                <td>" . ($row['role'] == 1 ? 'Admin' : 'Penilai') . "</td>
                                <td>
                                    <button class='btn btn-warning btn-sm' onclick='openUpdateModal(" . json_encode($row) . ")'><i class='fas fa-edit'></i></button>
                                    <a href='admin.php?delete=" . $row['idpengguna'] . "' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a>
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
                    <form method="POST" action="admin.php">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="1">Admin</option>
                                <option value="2">Penilai</option>
                            </select>
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
                    <form method="POST" action="admin.php">
                        <input type="hidden" id="updateId" name="id">
                        <div class="form-group">
                            <label for="updateUsername">Username</label>
                            <input type="text" class="form-control" id="updateUsername" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="updatePassword">Password</label>
                            <input type="password" class="form-control" id="updatePassword" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="updateRole">Role</label>
                            <select class="form-control" id="updateRole" name="role" required>
                                <option value="1">Admin</option>
                                <option value="2">Penilai</option>
                            </select>
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
            $('#updateId').val(data.idpengguna);
            $('#updateUsername').val(data.username);
            $('#updatePassword').val(data.password);
            $('#updateRole').val(data.role);
            $('#updateModal').modal('show');
        }
    </script>
</body>
</html>