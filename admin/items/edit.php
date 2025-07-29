<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

if (!isset($_GET['id'])) {
  header("Location: index.php");
  exit;
}

$item_id = intval($_GET['id']);

// Get item
$item_result = $mysqli->query("SELECT * FROM items WHERE id = $item_id");
$item = $item_result->fetch_assoc();
if (!$item) {
  header("Location: index.php");
  exit;
}

// Get current stall associations
$current_stalls = [];
$stall_result = $mysqli->query("SELECT stall_id FROM item_stalls WHERE item_id = $item_id");
while ($row = $stall_result->fetch_assoc()) {
  $current_stalls[] = $row['stall_id'];
}

// Get categories and stalls
$categories = $mysqli->query("SELECT * FROM categories ORDER BY name_en ASC");
$stalls = $mysqli->query("SELECT * FROM stalls ORDER BY business_name ASC");

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name_en = $_POST['name_en'];
  $name_tl = $_POST['name_tl'];
  $price = $_POST['price'];
  $category_id = $_POST['category_id'];
  $stall_ids = $_POST['stall_ids'] ?? [];

  $filename = $item['image'];

  // Handle new image upload
  if (!empty($_FILES['image']['name'])) {
    $filename = time() . '_' . basename($_FILES['image']['name']);
    $upload_path = '../../uploads/items/' . $filename;
    move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);
  }

  if ($name_en && $name_tl && $price && $category_id) {
    $stmt = $mysqli->prepare("UPDATE items SET name_en = ?, name_tl = ?, price = ?, image = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("ssdsii", $name_en, $name_tl, $price, $filename, $category_id, $item_id);
    $stmt->execute();

    // Clear and update item_stalls
    $mysqli->query("DELETE FROM item_stalls WHERE item_id = $item_id");

    $linkStmt = $mysqli->prepare("INSERT INTO item_stalls (item_id, stall_id) VALUES (?, ?)");
    foreach ($stall_ids as $stall_id) {
      $linkStmt->bind_param("ii", $item_id, $stall_id);
      $linkStmt->execute();
    }

    header("Location: index.php");
    exit;
  } else {
    $error = "All fields are required.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Item</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">

<div class="container">
  <h2 class="mb-4">Edit Item</h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="name_en" class="form-label">Item Name (English)</label>
      <input type="text" class="form-control" name="name_en" id="name_en" value="<?= htmlspecialchars($item['name_en']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="name_tl" class="form-label">Item Name (Tagalog)</label>
      <input type="text" class="form-control" name="name_tl" id="name_tl" value="<?= htmlspecialchars($item['name_tl']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="price" class="form-label">Price (₱)</label>
      <input type="number" step="0.01" class="form-control" name="price" id="price" value="<?= htmlspecialchars($item['price']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="category_id" class="form-label">Category</label>
      <select class="form-select" name="category_id" id="category_id" required>
        <option value="">Select Category</option>
        <?php while ($cat = $categories->fetch_assoc()): ?>
          <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $item['category_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['name_en']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="stall_ids" class="form-label">Available in Stalls</label>
      <select class="form-select" name="stall_ids[]" id="stall_ids" multiple required>
        <?php while ($stall = $stalls->fetch_assoc()): ?>
          <option value="<?= $stall['id'] ?>" <?= in_array($stall['id'], $current_stalls) ? 'selected' : '' ?>>
            <?= htmlspecialchars($stall['business_name']) ?> (<?= htmlspecialchars($stall['stall_number']) ?>)
          </option>
        <?php endwhile; ?>
      </select>
      <div class="form-text">Hold Ctrl (Windows) or Cmd (Mac) to select multiple stalls.</div>
    </div>

    <div class="mb-3">
      <label class="form-label">Current Image</label><br>
      <img src="../../uploads/items/<?= $item['image'] ?>" width="100" height="100" style="object-fit:cover">
    </div>

    <div class="mb-3">
      <label for="image" class="form-label">Change Image (optional)</label>
      <input type="file" class="form-control" name="image" id="image" accept="image/*">
    </div>

    <button type="submit" class="btn btn-success">Update Item</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
