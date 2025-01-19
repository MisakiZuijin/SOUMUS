<?php
session_start();
require '../../config/database.php';
require '../../config/functions.php';

if (!is_logged_in()) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
error_log("Input JSON diterima: " . json_encode($input));
$playlist_id = intval($input['playlist_id'] ?? 0);
$music_id = intval($input['music_id'] ?? 0);

error_log("Playlist ID diterima: $playlist_id");
error_log("Music ID diterima: $music_id");

if (!$playlist_id || !$music_id) {
    echo json_encode(['status' => 'error', 'message' => 'Playlist ID atau Music ID tidak valid.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO playlist_details (id_playlist, id_music) VALUES (?, ?)");
    $stmt->execute([$playlist_id, $music_id]);

    echo json_encode(['status' => 'success', 'message' => 'Lagu berhasil ditambahkan ke playlist.']);
} catch (PDOException $e) {
    error_log("Error adding song to playlist: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan lagu ke playlist.', 'details' => $e->getMessage()]);
}
?>