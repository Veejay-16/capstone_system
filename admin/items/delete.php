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

// Delete from item_stalls (many-to-many relation)
$mysqli->query("DELETE FROM item_stalls WHERE item_id = $id");

// Delete the item itself
$mysqli->query("DELETE FROM items WHERE id = $id");

header("Location: index.php");
exit;
