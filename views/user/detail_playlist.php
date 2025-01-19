<?php
session_start();
require '../../config/database.php';
require '../../config/functions.php';

if (!is_logged_in()) {
    redirect('../auth/login.php');
}

if (!isset($_GET['playlist_id'])) {
    die("Playlist tidak ditemukan.");
}

$playlist_id = intval($_GET['playlist_id']);
$details = get_playlist_details($pdo, $playlist_id);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add_song') {
        $music_id = intval($_POST['music_id']);
        $result = add_song_to_playlist($pdo, $playlist_id, $music_id);
        if ($result === true) {
            $success = "Lagu berhasil ditambahkan.";
            $details = get_playlist_details($pdo, $playlist_id);
        } else {
            $error = $result;
        }
    } elseif ($action === 'delete_song') {
        $detail_id = intval($_POST['detail_id']);
        $stmt = $pdo->prepare("DELETE FROM detail_playlist WHERE id = ?");
        $stmt->execute([$detail_id]);
        $success = "Lagu berhasil dihapus.";
        $details = get_playlist_details($pdo, $playlist_id);
    }
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

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <!-- Daftar lagu dalam playlist -->
    <h2>Daftar Lagu</h2>
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

    <!-- Tambah lagu ke playlist -->
    <h2>Tambah Lagu</h2>
    <form method="POST" action="">
        <input type="hidden" name="action" value="add_song">
        <select name="music_id">
            <?php
            $stmt = $pdo->query("SELECT id_music, title FROM music ORDER BY title ASC");
            while ($music = $stmt->fetch(PDO::FETCH_ASSOC)):
                echo "<option value=\"" . htmlspecialchars($music['id_music']) . "\">" . htmlspecialchars($music['title']) . "</option>";
            endwhile;
            ?>
        </select>
        <button type="submit">Tambahkan</button>
    </form>
</body>
</html>
