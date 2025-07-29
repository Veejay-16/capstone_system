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

$id = intval($_GET['id']);
$result = $mysqli->query("SELECT * FROM categories WHERE id = $id");
$category = $result->fetch_assoc();

if (!$category) {
  header("Location: index.php");
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name_en = $_POST['name_en'];
  $name_tl = $_POST['name_tl'];
  $section = $_POST['section'];

  $newImage = $_FILES['image']['name'];
  $filename = $category['image'];

  if ($name_en && $name_tl && $section) {
    if ($newImage) {
      $tmp = $_FILES['image']['tmp_name'];
      $filename = time() . '_' . basename($newImage);
      $target = '../../uploads/categories/' . $filename;
      move_uploaded_file($tmp, $target);
    }

    $stmt = $mysqli->prepare("UPDATE categories SET name_en=?, name_tl=?, section=?, image=? WHERE id=?");
    $stmt->bind_param("ssssi", $name_en, $name_tl, $section, $filename, $id);
    $stmt->execute();

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
  <title>Edit Category</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="p-4">

<div class="container">
  <h2 class="mb-4">Edit Category</h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="name_en" class="form-label">Name (English)</label>
      <input type="text" class="form-control" name="name_en" id="name_en" value="<?= htmlspecialchars($category['name_en']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="name_tl" class="form-label">Name (Tagalog)</label>
      <input type="text" class="form-control" name="name_tl" id="name_tl" value="<?= htmlspecialchars($category['name_tl']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="section" class="form-label">Section</label>
      <select class="form-select" name="section" id="section" required>
        <option value="">Select Section</option>
        <option value="wet" <?= $category['section'] === 'wet' ? 'selected' : '' ?>>Wet</option>
        <option value="dry" <?= $category['section'] === 'dry' ? 'selected' : '' ?>>Dry</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Current Image</label><br>
      <img src="../../uploads/categories/<?= $category['image'] ?>" width="100" height="100" style="object-fit:cover">
    </div>

    <div class="mb-3">
      <label for="image" class="form-label">Change Image (optional)</label>
      <input type="file" class="form-control" name="image" id="image" accept="image/*">
    </div>

    <button type="submit" class="btn btn-success">Update Category</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
