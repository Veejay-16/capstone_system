<?php
require_once '../includes/db.php';

$stall_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$result = $mysqli->query("SELECT * FROM stalls WHERE id = $stall_id");
$stall = $result->fetch_assoc();

if (!$stall) {
  echo "Stall not found.";
  exit;
}

$floor = $stall['floor'] ?? '1';
$map_image = ($floor === '2') ? 'floor2.png' : 'floor1.png';
$left_percent = floatval($stall['location_map_x_percent'] ?? 50);
$top_percent = floatval($stall['location_map_y_percent'] ?? 50);
$path_points = json_decode($stall['map_path_json'] ?? '[]', true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Market Map</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .map-container {
      position: relative;
      max-width: 1000px;
      margin: auto;
    }
    .map-container img {
      width: 100%;
      display: block;
    }
    .pin {
      position: absolute;
      width: 32px;
      height: 32px;
      background: url('../images/pin.png') no-repeat center center;
      background-size: contain;
      transform: translate(-50%, -100%);
      z-index: 10;
    }
    .path-point {
      position: absolute;
      width: 10px;
      height: 10px;
      background-color: red;
      border-radius: 50%;
      transform: translate(-50%, -50%);
    }
    svg.path-line {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 5;
    }
  </style>
</head>
<body class="bg-light">
<div class="container my-4">
  <h2 class="text-center mb-4">Map - <?= htmlspecialchars($stall['business_name']) ?> (<?= $stall['stall_number'] ?>)</h2>

  <div class="map-container mt-3" id="mapContainer">
    <img src="../images/<?= $map_image ?>" id="mapImage" alt="Market Map">
    <div class="pin" id="pin" style="left: <?= $left_percent ?>%; top: <?= $top_percent ?>%;"></div>
    <svg class="path-line" id="pathSVG"></svg>
    <?php if (!empty($path_points)): ?>
      <?php foreach ($path_points as $pt): ?>
        <div class="path-point" style="left: <?= $pt['x'] ?>%; top: <?= $pt['y'] ?>%;"></div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<!-- Back Button -->
<a href="javascript:history.back()" class="btn btn-black btn-lg position-fixed bottom-0 start-0 m-3 px-4 shadow">
  <i class="bi bi-arrow-left me-2"></i> Back
</a>

<!-- Done Button -->
<a href="index.php" class="btn btn-success btn-lg position-fixed bottom-0 end-0 m-3 px-5 shadow">
  <i class="bi bi-check-circle-fill me-2"></i> Done
</a>

<script>
let pathPoints = <?= json_encode($path_points) ?>;
let pathSVG = document.getElementById('pathSVG');

function drawPath() {
  pathSVG.innerHTML = '';
  for (let i = 0; i < pathPoints.length - 1; i++) {
    const p1 = pathPoints[i];
    const p2 = pathPoints[i + 1];
    const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
    line.setAttribute('x1', p1.x + '%');
    line.setAttribute('y1', p1.y + '%');
    line.setAttribute('x2', p2.x + '%');
    line.setAttribute('y2', p2.y + '%');
    line.setAttribute('stroke', 'red');
    line.setAttribute('stroke-width', '2');
    line.setAttribute('stroke-dasharray', '5,5');
    pathSVG.appendChild(line);
  }
}

drawPath();
</script>
</body>
</html>
