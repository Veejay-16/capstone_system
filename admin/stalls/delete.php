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

// First, optionally delete item associations if you have a linking table
$mysqli->query("DELETE FROM item_stalls WHERE stall_id = $id");

// Delete the stall
$mysqli->query("DELETE FROM stalls WHERE id = $id");

header("Location: index.php");
exit;
