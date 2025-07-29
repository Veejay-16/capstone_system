<?php
require_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST body
    $data = json_decode(file_get_contents('php://input'), true);

    $stall_id = intval($data['id']);
    $x_percent = floatval($data['x_percent']);
    $y_percent = floatval($data['y_percent']);
    $path_json = json_encode($data['path_points']); // The array of path coordinates

    $stmt = $mysqli->prepare("UPDATE stalls SET location_map_x_percent = ?, location_map_y_percent = ?, map_path_json = ? WHERE id = ?");
    $stmt->bind_param("ddsi", $x_percent, $y_percent, $path_json, $stall_id);

    if ($stmt->execute()) {
        echo "Changes saved successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    exit;
}
?>
s