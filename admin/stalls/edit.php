<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$result = $mysqli->query("SELECT * FROM stalls WHERE id = $id");
$stall = $result->fetch_assoc();

if (!$stall) {
    echo "Stall not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $business_name = $_POST['business_name'];
    $stall_number = $_POST['stall_number'];
    $floor = $_POST['floor'];
    $x = $_POST['location_map_x'];
    $y = $_POST['location_map_y'];

    $stmt = $mysqli->prepare("UPDATE stalls SET business_name = ?, stall_number = ?, floor = ?, location_map_x = ?, location_map_y = ? WHERE id = ?");
    $stmt->bind_param("sssiii", $business_name, $stall_number, $floor, $x, $y, $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Stall</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2 class="mb-4">Edit Stall</h2>
  <form method="post">
    <div class="mb-3">
      <label for="business_name" class="form-label">Business Name</label>
      <input type="text" name="business_name" id="business_name" class="form-control" required value="<?= htmlspecialchars($stall['business_name']) ?>">
    </div>
    <div class="mb-3">
      <label for="stall_number" class="form-label">Stall Number</label>
      <input type="text" name="stall_number" id="stall_number" class="form-control" required value="<?= htmlspecialchars($stall['stall_number']) ?>">
    </div>
    <div class="mb-3">
      <label for="floor" class="form-label">Floor</label>
      <select name="floor" id="floor" class="form-control" required>
        <option value="1" <?= $stall['floor'] == '1' ? 'selected' : '' ?>>1st Floor</option>
        <option value="2" <?= $stall['floor'] == '2' ? 'selected' : '' ?>>2nd Floor</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="location_map_x" class="form-label">Map X Coordinate</label>
      <input type="number" name="location_map_x" id="location_map_x" class="form-control" required value="<?= $stall['location_map_x'] ?>">
    </div>
    <div class="mb-3">
      <label for="location_map_y" class="form-label">Map Y Coordinate</label>
      <input type="number" name="location_map_y" id="location_map_y" class="form-control" required value="<?= $stall['location_map_y'] ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update Stall</button>
  </form>
</div>
</body>
</html>
