<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

$categories = $mysqli->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin | Manage Categories</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    .thumbnail-img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 8px;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }
  </style>
</head>
<body class="bg-light p-4">

<div class="container">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0"><i class="bi bi-tags-fill me-2"></i>Manage Categories</h3>
      <a href="add.php" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Add Category
      </a>
    </div>

    <div class="table-responsive">
      <table id="categoryTable" class="table table-bordered table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name (EN)</th>
            <th>Name (TL)</th>
            <th>Section</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $categories->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><img src="../../uploads/categories/<?= $row['image'] ?>" class="thumbnail-img" alt="category image"></td>
              <td><?= htmlspecialchars($row['name_en']) ?></td>
              <td><?= htmlspecialchars($row['name_tl']) ?></td>
              <td><?= ucfirst($row['section']) ?></td>
              <td class="text-center">
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning me-1">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category?')">
                  <i class="bi bi-trash-fill"></i>
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      <a href="../index.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left-circle me-1"></i> Back to Dashboard
      </a>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(document).ready(function() {
    $('#categoryTable').DataTable();
  });
</script>

</body>
</html>
