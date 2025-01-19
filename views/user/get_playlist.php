<?php
session_start();
require '../../config/database.php';
require '../../config/functions.php';

if (!is_logged_in()) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login.']);
    exit;
}

$user_id = $_SESSION['user_id'];

$playlists = get_playlists($pdo, $user_id);

if ($playlists === false) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengambil playlist.']);
} else {
    echo json_encode(['status' => 'success', 'playlists' => $playlists]);
}
?>
