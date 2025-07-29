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

// Get the category to delete
$result = $mysqli->query("SELECT * FROM categories WHERE id = $id");
$category = $result->fetch_assoc();

if ($category) {
  // Delete the image file
  $imagePath = '../../uploads/categories/' . $category['image'];
  if (file_exists($imagePath)) {
    unlink($imagePath);
  }

  // Delete the category record
  $mysqli->query("DELETE FROM categories WHERE id = $id");
}

header("Location: index.php");
exit;
