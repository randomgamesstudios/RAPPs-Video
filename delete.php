<?php
session_start();
$id = $_GET['id'] ?? '';
$metaPath = "data/$id.json";

if (!file_exists($metaPath)) {
    die("Video not found.");
}

$data = json_decode(file_get_contents($metaPath), true);

if (!isset($_SESSION['user']) || $_SESSION['user'] !== $data['user']) {
    die("Access denied.");
}

// Delete files
@unlink($data['video']);
@unlink($data['thumb']);
@unlink($metaPath);

header("Location: videos.php");
exit;
