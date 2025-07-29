<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

$result = $mysqli->query("SELECT items.*, categories.name_en AS category_name 
                          FROM items 
                          LEFT JOIN categories ON items.category_id = categories.id 
                          ORDER BY items.id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Items</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
</head>
<body class="p-4">

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Products/Items</h2>
    <a href="add.php" class="btn btn-primary">Add New Product/Item</a>
  </div>

  <div class="table-responsive">
    <table id="itemsTable" class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>Image</th>
          <th>Name (EN)</th>
          <th>Name (TL)</th>
          <th>Price</th>
          <th>Category</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td>
              <img src="../../uploads/items/<?= htmlspecialchars($row['image']) ?>" width="60" height="60" style="object-fit: cover;" class="rounded" />
            </td>
            <td><?= htmlspecialchars($row['name_en']) ?></td>
            <td><?= htmlspecialchars($row['name_tl']) ?></td>
            <td>₱<?= number_format($row['price'], 2) ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td>
              <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <a href="../index.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(document).ready(function () {
    $('#itemsTable').DataTable();
  });
</script>

</body>
</html>
