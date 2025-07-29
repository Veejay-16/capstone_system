<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

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
  <title>Edit Stall Map & Path</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .map-container {
      position: relative;
      max-width: 1000px;
      margin: auto;
      border: 1px solid #dee2e6;
      border-radius: 10px;
      overflow: hidden;
    }
    .map-container img {
      width: 100%;
      display: block;
    }
    .pin {
      position: absolute;
      width: 32px;
      height: 32px;
      background: url('../../images/pin.png') no-repeat center center;
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
      cursor: move;
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
    .btn-group-custom {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: center;
    }
  </style>
</head>
<body>
<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">🗺️ Edit Stall Map & Path</h4>
    <div>
      <a href="index.php" class="btn btn-secondary">← Back to Stall List</a>
    </div>
  </div>

  <div class="alert alert-info text-center">
    <strong></strong> Tap anywhere to place the <strong>pin</strong>. Hold <kbd>Shift</kbd> and click to draw a <strong>path</strong>.
  </div>

  <div class="card p-3 mb-3">
    <h5 class="text-center"><?= htmlspecialchars($stall['business_name']) ?> (<?= $stall['stall_number'] ?>)</h5>
    <div class="map-container mt-3" id="mapContainer">
      <img src="../../images/<?= $map_image ?>" id="mapImage" alt="Market Map">
      <div class="pin" id="pin" style="left: <?= $left_percent ?>%; top: <?= $top_percent ?>%;" data-x="<?= $left_percent ?>" data-y="<?= $top_percent ?>"></div>
      <svg class="path-line" id="pathSVG"></svg>
    </div>
  </div>

  <div class="btn-group-custom mt-3">
    <button class="btn btn-success" onclick="saveData()"> Save Changes</button>
    <button class="btn btn-danger" onclick="clearPath()"> Clear Path</button>
  </div>
</div>

<script>
let pin = document.getElementById('pin');
let mapImage = document.getElementById('mapImage');
let mapContainer = document.getElementById('mapContainer');
let pathSVG = document.getElementById('pathSVG');
let pathPoints = <?= json_encode($path_points) ?>;

mapContainer.addEventListener('click', function(e) {
  const rect = mapImage.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;
  const percentX = (x / rect.width) * 100;
  const percentY = (y / rect.height) * 100;

  if (e.shiftKey) {
    pathPoints.push({ x: percentX, y: percentY });
    drawPath();
  } else {
    pin.style.left = percentX + '%';
    pin.style.top = percentY + '%';
    pin.dataset.x = percentX;
    pin.dataset.y = percentY;
  }
});

function drawPath() {
  pathSVG.innerHTML = '';
  document.querySelectorAll('.path-point').forEach(el => el.remove());

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

  pathPoints.forEach((pt, index) => {
    const dot = document.createElement('div');
    dot.className = 'path-point';
    dot.style.left = pt.x + '%';
    dot.style.top = pt.y + '%';
    dot.setAttribute('data-index', index);
    dot.addEventListener('mousedown', startDrag);
    mapContainer.appendChild(dot);
  });
}

let draggedDot = null;

function startDrag(e) {
  draggedDot = e.target;
  document.addEventListener('mousemove', drag);
  document.addEventListener('mouseup', stopDrag);
}

function drag(e) {
  if (!draggedDot) return;
  const rect = mapImage.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;

  const percentX = (x / rect.width) * 100;
  const percentY = (y / rect.height) * 100;

  const index = parseInt(draggedDot.dataset.index);
  pathPoints[index].x = percentX;
  pathPoints[index].y = percentY;

  drawPath();
}

function stopDrag() {
  document.removeEventListener('mousemove', drag);
  document.removeEventListener('mouseup', stopDrag);
  draggedDot = null;
}

function saveData() {
  const x = pin.dataset.x || <?= $left_percent ?>;
  const y = pin.dataset.y || <?= $top_percent ?>;

  fetch('map_editor_save.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      id: <?= $stall_id ?>,
      x_percent: x,
      y_percent: y,
      path_points: pathPoints
    })
  })
  .then(res => res.text())
  .then(msg => {
    alert(msg);
    location.reload();
  });
}

function clearPath() {
  if (confirm("Clear all path points?")) {
    pathPoints = [];
    drawPath();
  }
}

drawPath();
</script>
</body>
</html>
