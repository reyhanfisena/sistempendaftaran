<?php
require 'config.php';
session_start();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT idpengguna, role FROM pengguna WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->bind_result($idpengguna, $role);
    $stmt->fetch();

    if ($idpengguna) {
        $_SESSION['idpengguna'] = $idpengguna;
        $_SESSION['role'] = $role;
        $success = 'Login berhasil!';

        if ($role == 1) {
            header("Location: admin/index.php");
            exit();
        } else if ($role == 2) {
            header("Location: penilai/index.php");
            exit();
        } else {
            $error = "Invalid role";
        }
    } else {
        $error = "Username or password is incorrect";
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
    <title>Login</title>
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
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .login-container .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .login-container .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .login-container .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .login-container .form-group {
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
        .login-container img {
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
</head>
<body>
    <div class="login-container">
        <div class="text-center">
            <img src="logo.png" alt="Login Icon">
        </div>
        <h2 class="text-center">Login <br>Admin dan Tim Penilai</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php elseif ($success): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group input-icon">
                <i class="fas fa-user"></i>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="form-group input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
    </div>
    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
