<?php
session_start(); // Mulai sesi
require '../../config/database.php';
require '../../config/functions.php';

// Pastikan pengguna sudah login
if (!is_logged_in()) {
    redirect('../auth/login.php');
}

// Pastikan ID user tersedia di sesi
if (!isset($_SESSION['user_id'])) {
    die("Sesi tidak ditemukan. Pastikan pengguna sudah login.");
}

$user_id = $_SESSION['user_id']; // Ambil ID user dari sesi
error_log("ID User dari sesi: $user_id"); // Debugging

$error = '';
$success = '';

// Tangani pembuatan playlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $result = create_playlist($pdo, $name, $user_id);
        if ($result === true) {
            $success = "Playlist berhasil dibuat.";
        } else {
            $error = $result;
        }
    } elseif ($action === 'delete') {
        $playlist_id = intval($_POST['playlist_id']);
        $result = delete_playlist($pdo, $playlist_id, $user_id);
        if ($result === true) {
            $success = "Playlist berhasil dihapus.";
        } else {
            $error = $result;
        }
    } elseif ($action === 'add_song') {
        $playlist_id = intval($_POST['playlist_id']);
        $music_id = intval($_POST['music_id']);
        $result = add_song_to_playlist($pdo, $playlist_id, $music_id);
        if ($result === true) {
            $success = "Lagu berhasil ditambahkan ke playlist.";
        } else {
            $error = $result;
        }
    }
}

// Ambil daftar playlist
$playlists = get_playlists($pdo, $user_id);

// Ambil detail playlist (jika ada)
$details = [];
$selected_playlist = null;
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['playlist_id'])) {
    $selected_playlist = intval($_GET['playlist_id']);
    $details = get_playlist_details($pdo, $selected_playlist);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist</title>
</head>
<body>
    <h1>Playlist Anda</h1>
    <a href="home.php">Kembali ke Beranda</a>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <!-- Daftar playlist -->
    <h2>Daftar Playlist</h2>
    <ul>
        <?php foreach ($playlists as $playlist): ?>
            <?php error_log("Generated Playlist Link: detail_playlist.php?playlist_id=" . $playlist['id_playlist']); ?>
            <li>
                <a href="detail_playlist.php?id_playlist=<?= htmlspecialchars($playlist['id_playlist']) ?>">
                    <?= htmlspecialchars($playlist['name']) ?>
                </a>
                <form method="POST" action="playlist.php" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="playlist_id" value="<?= htmlspecialchars($playlist['id_playlist']) ?>">
                    <button type="submit" onclick="return confirm('Hapus playlist ini?')">Hapus</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Modal Tambah Playlist -->
    <div id="createPlaylistModal" class="modal">
        <form method="POST" action="playlist.php">
            <input type="hidden" name="action" value="create">
            <input type="text" name="name" placeholder="Nama Playlist" required>
            <button type="submit">Simpan</button>
            <button type="button" id="closeCreatePlaylistModal">Batal</button>
        </form>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="overlay"></div>

    <script>
        const createModal = document.getElementById('createPlaylistModal');
        const overlay = document.getElementById('overlay');

        document.getElementById('createPlaylistModal').addEventListener('click', () => {
            createModal.classList.add('active');
            overlay.classList.add('active');
        });

        document.getElementById('closeCreatePlaylistModal').addEventListener('click', () => {
            createModal.classList.remove('active');
            overlay.classList.remove('active');
        });

        overlay.addEventListener('click', () => {
            createModal.classList.remove('active');
            overlay.classList.remove('active');
        });
    </script>
</body>
</html>