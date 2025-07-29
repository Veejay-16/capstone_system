<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $business_name = $_POST['business_name'];
    $stall_number = $_POST['stall_number'];
    $floor = $_POST['floor'];
    $x = $_POST['location_map_x'];
    $y = $_POST['location_map_y'];

    $stmt = $mysqli->prepare("INSERT INTO stalls (business_name, stall_number, floor, location_map_x, location_map_y) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $business_name, $stall_number, $floor, $x, $y);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Stall</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2 class="mb-4">Add Stall</h2>
  <form method="post">
    <div class="mb-3">
      <label for="business_name" class="form-label">Business Name</label>
      <input type="text" name="business_name" id="business_name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="stall_number" class="form-label">Stall Number</label>
      <input type="text" name="stall_number" id="stall_number" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="floor" class="form-label">Floor</label>
      <select name="floor" id="floor" class="form-control" required>
        <option value="1">1st Floor</option>
        <option value="2">2nd Floor</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="location_map_x" class="form-label">Map X Coordinate</label>
      <input type="number" name="location_map_x" id="location_map_x" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="location_map_y" class="form-label">Map Y Coordinate</label>
      <input type="number" name="location_map_y" id="location_map_y" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Add Stall</button>
  </form>
</div>
</body>
</html>
