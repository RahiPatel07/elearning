<?php
header('Content-Type: application/json');
require_once '../config.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';

if ($category) {
    $stmt = $conn->prepare("SELECT * FROM playlist WHERE status = 'active' AND category = :category");
    $stmt->bindParam(':category', $category);
    $stmt->execute();
    $playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($playlists);
} else {
    echo json_encode([]);
}
?> 