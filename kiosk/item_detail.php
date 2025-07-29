<?php
require_once '../includes/db.php';
require_once '../lang/' . ($_SESSION['lang'] ?? 'en') . '.php';

$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$item_result = $mysqli->query("SELECT * FROM items WHERE id = $item_id");
$item = $item_result->fetch_assoc();

if (!$item) {
    echo "Item not found.";
    exit;
}

$stalls_result = $mysqli->query("
    SELECT stalls.*
    FROM stalls
    INNER JOIN item_stalls ON stalls.id = item_stalls.stall_id
    WHERE item_stalls.item_id = $item_id
");
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($item['name_en']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom right, #e3f2fd, #60a2d8ff);
      min-height: 100vh;
    }
    .item-image {
      max-height: 250px;
      object-fit: cover;
      border-radius: 12px;
      border: 3px solid #f8f8f8ff;
    }
    .stall-card {
      transition: transform 0.2s ease;
    }
    .stall-card:hover {
      transform: scale(1.02);
    }
    .back-btn {
      text-decoration: none;
      font-weight: 500;
    }
  </style>
</head>
<body>
<div class="container py-4">
  <a href="javascript:history.back()" class="btn btn-outline-secondary mb-3 back-btn">
    <i class="bi bi-arrow-left"></i> Back
  </a>

  <div class="text-center mb-4">
    <h2 class="fw-bold text-dark"><?= htmlspecialchars($item['name_en']) ?></h2>
    <img src="../uploads/items/<?= htmlspecialchars($item['image']) ?>" class="img-fluid item-image shadow-sm my-3" alt="<?= htmlspecialchars($item['name_en']) ?>">
    <p class="text-muted fs-5">Price: ₱<?= number_format($item['price'], 2) ?></p>
  </div>

  <h4 class="text-dark mb-3"><i class="bi bi-shop-window me-1"></i> Available at these stalls:</h4>
  <div class="row">
    <?php while ($stall = $stalls_result->fetch_assoc()): ?>
      <div class="col-md-6">
        <div class="card mb-3 shadow-sm stall-card">
          <div class="card-body">
            <h5 class="card-title text-dark">
              <i class="bi bi-person-circle me-1 text-primary"></i>
              <?= htmlspecialchars($stall['business_name']) ?>
            </h5>
            <p class="card-text mb-2">
              <i class="bi bi-shop me-1 text-secondary"></i> Stall #: <strong><?= htmlspecialchars($stall['stall_number']) ?></strong><br>
              <span class="badge bg-info text-dark">
                <?= $stall['floor'] == '2' ? '2nd Floor' : '1st Floor' ?>
              </span>
            </p>
            <a href="map.php?id=<?= $stall['id'] ?>" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-geo-alt"></i> View on Map
            </a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>
</body>
</html>
