<?php
require_once '../includes/db.php';
session_start();

$section = $_GET['section'] ?? 'wet';
$categoryId = $_GET['category_id'] ?? null;
$search = $_GET['search'] ?? null;

// Build base SQL
$sql = "
  SELECT items.*, categories.name_en AS cat_en, categories.name_tl AS cat_tl
  FROM items
  JOIN categories ON items.category_id = categories.id
  WHERE categories.section = ?
";

// Add filters
$params = [$section];
$types = "s";

if ($categoryId && $categoryId !== 'all') {
  $sql .= " AND categories.id = ?";
  $params[] = $categoryId;
  $types .= "i";
}

if ($search) {
  $sql .= " AND (items.name_en LIKE ? OR items.name_tl LIKE ?)";
  $params[] = "%$search%";
  $params[] = "%$search%";
  $types .= "ss";
}

$sql .= " ORDER BY items.name_en ASC";

// Prepare and execute
$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

while ($item = $result->fetch_assoc()):
?>
  <div class="col-md-4">
    <div class="card item-card h-100" onclick="location.href='item_detail.php?id=<?= $item['id'] ?>'">
      <img src="../uploads/items/<?= $item['image'] ?>" class="card-img-top" alt="<?= $item['name_en'] ?>">
      <div class="card-body">
        <h5 class="card-title"><?= $_SESSION['lang'] === 'tl' ? $item['name_tl'] : $item['name_en'] ?></h5>
        <p class="card-text"><?= $_SESSION['lang'] === 'tl' ? 'Presyo' : 'Price' ?>: ₱<?= number_format($item['price'], 2) ?></p>
      </div>
    </div>
  </div>
<?php endwhile; ?>
