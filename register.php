<?php
require 'config.php';
session_start();

$error = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $alamat = $_POST['alamat'];
    $asalsekolah = $_POST['asalsekolah'];
    $nisn = $_POST['nisn'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO pendaftar (nama, email, password, alamat, asalsekolah, nisn) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nama, $email, $password, $alamat, $asalsekolah, $nisn);

    if ($stmt->execute()) {
        // Registration successful
        $success = true;
    } else {
        $error = 'Terjadi kesalahan. Silakan coba lagi.';
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: url('images.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .register-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .register-container .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .register-container .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .register-container .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }
        .register-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .register-container .form-group {
            margin-bottom: 20px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .icon {
            font-size: 50px;
            color: #007bff;
            margin-bottom: 20px;
        }
        .register-container img {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
        }
        .input-icon {
            position: relative;
        }
        .input-icon i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #007bff;
        }
        .input-icon input {
            padding-left: 40px;
        }
    </style>
    <script>
        function redirectToLogin() {
            setTimeout(function() {
                window.location.href = 'loginpendaftar.php';
            }, 3000); // 3 seconds delay
        }
    </script>
</head>
<body>
    <div class="register-container">
        <div class="text-center">
            <img src="logo.png" alt="Register Icon">
        </div>
        <h2 class="text-center">Register</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                Registrasi berhasil! Anda akan diarahkan ke halaman login dalam beberapa detik.
            </div>
            <script>
                redirectToLogin();
            </script>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group input-icon">
                <i class="fas fa-user"></i>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" required>
            </div>
            <div class="form-group input-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group input-icon">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" required>
            </div>
            <div class="form-group input-icon">
                <i class="fas fa-school"></i>
                <input type="text" class="form-control" id="asalsekolah" name="asalsekolah" placeholder="Asal Sekolah" required>
            </div>
            <div class="form-group input-icon">
                <i class="fas fa-id-card"></i>
                <input type="text" class="form-control" id="nisn" name="nisn" placeholder="NISN" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
        <div class="text-center">
            <p>Sudah punya akun? <a href="loginpendaftar.php">Login</a></p>
        </div>
    </div>
    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
