<?php
require_once '../includes/db.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit;
}

$valid_username = 'admin';
$valid_password = 'admin123';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($username === $valid_username && $password === $valid_password) {
    $_SESSION['admin'] = $username;
    header("Location: index.php");
    exit;
  } else {
    $error = 'Invalid username or password.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body {
      background: url('../images/admin-bg.jpg') no-repeat center center;
      background-size: cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .overlay {
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.5);
      z-index: 1;
    }

    .login-card {
      z-index: 2;
      position: relative;
      max-width: 400px;
      width: 100%;
      padding: 2.5rem;
      background: #ffffffdd;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.2);
      backdrop-filter: blur(4px);
    }

    .login-card h3 {
      font-weight: 600;
    }

    .btn-primary {
      background-color: #0069d9;
      border-color: #0062cc;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #004a9f;
    }

    .form-control {
      height: 45px;
    }
  </style>
</head>
<body>
<div class="overlay"></div>

<div class="login-card">
  <h3 class="text-center mb-4">Admin Login</h3>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" autocomplete="off">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" name="username" class="form-control" required autofocus>
    </div>
    <div class="mb-4">
      <label for="password" class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>
</div>

</body>
</html>
