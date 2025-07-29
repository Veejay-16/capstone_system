<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

$result = $mysqli->query("SELECT * FROM stalls ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Stalls</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Stalls</h2>
    <a href="add.php" class="btn btn-success">Add Stall</a>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped" id="stallsTable">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Business Name</th>
          <th>Stall Number</th>
          <th>Floor</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['business_name']) ?></td>
          <td><?= htmlspecialchars($row['stall_number']) ?></td>
          <td><?= htmlspecialchars($row['floor']) ?></td>
          <td>
            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
            <a href="map_editor.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit Map</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <a href="../index.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(document).ready(function () {
    $('#stallsTable').DataTable();
  });
</script>
</body>
</html>
