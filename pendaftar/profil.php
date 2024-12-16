<?php
// Memulai sesi
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['idpendaftar'])) {
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Koneksi ke database
include('../config.php');

// Ambil data profil berdasarkan idpendaftar dari sesi
$idpendaftar = $_SESSION['idpendaftar'];
$sql = "SELECT * FROM pendaftar WHERE idpendaftar = $idpendaftar";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "0 results";
    exit();
}

// Update data profil jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomor_telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];
    
    // Untuk mengunggah file gambar
    if (isset($_FILES['profilePictureUpload']) && $_FILES['profilePictureUpload']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profilePictureUpload']['tmp_name'];
        $fileName = $_FILES['profilePictureUpload']['name'];
        $fileSize = $_FILES['profilePictureUpload']['size'];
        $fileType = $_FILES['profilePictureUpload']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Tentukan direktori penyimpanan
        $uploadFileDir = '../upload_files/';
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }
        $newFileName = $idpendaftar . '.' . $fileExtension;
        $dest_path = $uploadFileDir . $newFileName;

        // Pindahkan file ke direktori tujuan
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $profilePicture = $newFileName;
        } else {
            echo "Error uploading file. ";
            $profilePicture = $row['img']; // Jika gagal mengunggah, gunakan gambar lama
        }
    } else {
        $profilePicture = $row['img']; // Jika tidak ada file yang diunggah, gunakan gambar lama
    }

    // Update data profil
    $sql = "UPDATE pendaftar SET notelepon='$nomor_telepon', alamat='$alamat', img='$profilePicture' WHERE idpendaftar=$idpendaftar";

    if ($conn->query($sql) === TRUE) {
        header("Location: profil.php"); // Redirect ke halaman profil setelah update
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEM PENDAFTARAN MANDIRI - Profil</title>
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
        .profile-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .profile-card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-info {
            margin-top: 20px;
        }
        .profile-info p {
            margin: 10px 0;
            font-size: 1.1rem;
        }
        .profile-info p strong {
            width: 150px;
            display: inline-block;
        }
        .btn-update {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
        }
        .form-control {
            font-size: 1.1rem;
        }
        .form-group img {
            display: block;
            margin-bottom: 10px;
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
        <a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a>
        <a href="profil.php" class="active"><i class="fas fa-user"></i> Profil</a>
        <a href="../logoutpendaftar.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="header">
        <h2>Profil</h2>
    </div>

    <div class="main-content">
        <div class="profile-card">
            <form action="profil.php" method="post" enctype="multipart/form-data">
                <div class="text-center">
                    <img src="<?php echo $row['img'] ? '../upload_files/' . $row['img'] : 'path/to/your/default/profile.jpg'; ?>" alt="Profile Picture" id="profilePicture">
                </div>
                <div class="form-group">
                    <label for="profilePictureUpload"><strong>Ganti Foto Profil:</strong></label>
                    <input type="file" class="form-control-file" id="profilePictureUpload" name="profilePictureUpload" accept="image/*" onchange="previewImage(event)">
                </div>
                <div class="profile-info">
                    <div class="form-group">
                        <label for="nama"><strong>Nama:</strong></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $row['nama']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nisn"><strong>NISN:</strong></label>
                        <input type="text" class="form-control" id="nisn" name="nisn" value="<?php echo $row['nisn']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email"><strong>Email:</strong></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nomor_telepon"><strong>Nomor Telepon:</strong></label>
                        <input type="tel" class="form-control" id="nomor_telepon" name="nomor_telepon" value="<?php echo $row['notelepon']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="alamat"><strong>Alamat:</strong></label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $row['alamat']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="asal_sekolah"><strong>Asal Sekolah:</strong></label>
                        <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah" value="<?php echo $row['asalsekolah']; ?>" readonly>
                    </div>
                </div>
                <button type="submit" class="btn btn-update"><i class="fas fa-edit"></i> Update Profil</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Sistem Pendaftaran Mandiri UPN Veteran Jawa Timur</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('profilePicture');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
