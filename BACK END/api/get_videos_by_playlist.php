<?php
header('Content-Type: application/json');
require_once '../config.php';

$playlist_id = isset($_GET['playlist_id']) ? $_GET['playlist_id'] : '';

if ($playlist_id) {
    $stmt = $conn->prepare("SELECT * FROM content WHERE playlist_id = :playlist_id AND status = 'active'");
    $stmt->bindParam(':playlist_id', $playlist_id);
    $stmt->execute();
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($videos);
} else {
    echo json_encode([]);
}
?> 