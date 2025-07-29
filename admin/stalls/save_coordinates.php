<?php
require_once '../../includes/db.php';
require_once '../../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $x = $_POST['x'] ?? null;
    $y = $_POST['y'] ?? null;
    $floor = $_POST['floor'] ?? '1';

    if ($id && $x !== null && $y !== null) {
        $stmt = $mysqli->prepare("UPDATE stalls SET location_map_x = ?, location_map_y = ?, floor = ? WHERE id = ?");
        $stmt->bind_param("ddsi", $x, $y, $floor, $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: index.php");
    exit;
}
?>
