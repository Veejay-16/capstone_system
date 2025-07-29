<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("Missing or invalid ID from URL.");
}

$stall_id = intval($_GET['id']);
$result = $mysqli->query("SELECT * FROM stalls WHERE id = $stall_id");

if (!$result || $result->num_rows === 0) {
  die("Stall not found with ID = $stall_id");
}

$stall = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Stall Location Map</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    #map-container {
      position: relative;
      width: 100%;
      max-width: 800px;
      margin: auto;
    }
    .market-map {
      width: 100%;
      display: block;
    }
    .pin {
      position: absolute;
      width: 30px;
      height: 30px;
      transform: translate(-50%, -100%);
      pointer-events: none;
    }
    .back-btn {
      position: absolute;
      top: 15px;
      left: 15px;
      z-index: 10;
    }
  </style>
</head>
<body class="bg-light">

<?php if ($stall): ?>
  <div class="container py-4">
    <div class="text-center mb-3">
      <h3>Stall #<?= htmlspecialchars($stall['stall_number']) ?> — <?= htmlspecialchars($stall['business_name']) ?></h3>
      <p>Location in the public market (Floor <?= $stall['floor'] ?>)</p>
    </div>

    <div id="map-container">
      <a href="javascript:history.back()" class="btn btn-secondary back-btn">← Back</a>
      <img src="../images/map_floor<?= $stall['floor'] == '2' ? '2' : '1' ?>.png" class="market-map" alt="Market Map">

      <?php if ($stall['location_map_x'] !== null && $stall['location_map_y'] !== null): ?>
        <img src="../images/pin.png" class="pin" style="left: <?= $stall['location_map_x'] ?>%; top: <?= $stall['location_map_y'] ?>%;">
      <?php endif; ?>
    </div>
  </div>
<?php else: ?>
  <div class="container text-center mt-5">
    <h4>Stall not found.</h4>
    <a href="javascript:history.back()" class="btn btn-primary mt-3">Back</a>
  </div>
<?php endif; ?>

</body>
</html>
