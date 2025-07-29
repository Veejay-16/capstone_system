<?php
require_once '../includes/db.php';

session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
  <style>
    body {
      height: 100vh;
      overflow: hidden;
    }
    .sidebar {
      background-color: #343a40;
      color: white;
      padding: 20px;
      height: 100vh;
    }
    .sidebar h4 {
      color: #ffc107;
    }
    .sidebar a {
      color: #ddd;
      display: flex;
      align-items: center;
      padding: 10px 0;
      text-decoration: none;
      font-size: 16px;
    }
    .sidebar a i {
      margin-right: 10px;
    }
    .sidebar a:hover {
      color: white;
      text-decoration: none;
    }
    .logout-btn {
      position: absolute;
      bottom: 20px;
      width: calc(100% - 40px);
    }
    .main {
      padding: 40px;
      background: #f8f9fa;
      height: 100vh;
      overflow-y: auto;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 sidebar position-relative d-flex flex-column">
      <h4 class="mb-4"><i class="bi bi-speedometer2"></i> Admin Panel</h4>
      <a href="categories/index.php"><i class="bi bi-tags"></i> Manage Categories</a>
      <a href="items/index.php"><i class="bi bi-box"></i> Manage Products</a>
      <a href="stalls/index.php"><i class="bi bi-shop"></i> Manage Stalls</a>
      <a href="logout.php" class="btn btn-danger mt-auto logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="col-md-9 main">
      <h2>Welcome, Admin</h2>
      <p>Use the sidebar to manage the kiosk data.</p>
    </div>
  </div>
</div>

</body>
</html>
