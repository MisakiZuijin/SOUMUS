<?php
session_start();
require '../../config/database.php';
require '../../config/functions.php';

if (!is_logged_in()) {
    redirect('../auth/login.php');
}

if (!isset($_GET['id_playlist']) || empty($_GET['id_playlist'])) {
    error_log("Playlist ID is missing or empty in the URL");
    die("Playlist tidak ditemukan.");
}

$playlist_id = intval($_GET['id_playlist']);
error_log("Playlist ID from URL: $playlist_id");

$playlist_id = intval($_GET['id_playlist']);
$details = get_playlist_details($pdo, $playlist_id);

$error = '';
$success = '';

// Hapus lagu dari playlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_song') {
    $detail_id = intval($_POST['detail_id']);
    $stmt = $pdo->prepare("DELETE FROM playlist_details WHERE id_playlist = ? AND id_music = ?");
    $stmt->execute([$detail_id]);
    $success = "Lagu berhasil dihapus.";
    $details = get_playlist_details($pdo, $playlist_id); // Refresh daftar lagu
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Playlist</title>
</head>
<body>
    <h1>Detail Playlist</h1>
    <a href="playlist.php">Kembali ke Daftar Playlist</a>

    <!-- Tampilkan pesan kesalahan atau sukses -->
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <h2>Daftar Lagu</h2>
    <?php if (empty($details)): ?>
        <p>Tidak ada lagu dalam playlist ini.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($details as $detail): ?>
                <li>
                    <?= htmlspecialchars($detail['title']) ?> - <?= htmlspecialchars($detail['artist']) ?>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="action" value="delete_song">
                        <input type="hidden" name="detail_id" value="<?= htmlspecialchars($detail['id']) ?>">
                        <button type="submit" onclick="return confirm('Hapus lagu ini dari playlist?')">Hapus</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>