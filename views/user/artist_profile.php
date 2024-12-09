<?php
session_start();
require '../../config/database.php';
require '../../config/functions.php';

if (!is_logged_in()) {
    redirect('../auth/login.php');
}

// Ambil ID artis dari parameter URL
if (isset($_GET['id'])) {
    $artist_id = $_GET['id'];

    // Ambil data artis dari tabel users
    $stmt = $pdo->prepare("SELECT artist_name, email, region FROM users WHERE id_user = ?");
    $stmt->execute([$artist_id]);
    $artist = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$artist) {
        die("Artis tidak ditemukan.");
    }

    // Ambil daftar musik yang diunggah oleh artis
    $stmt = $pdo->prepare("SELECT id_music, title, upload_date, image FROM music WHERE id_user = ?");
    $stmt->execute([$artist_id]);
    $music_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("ID artis tidak diberikan.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Artis</title>
</head>
<body>
    <header>
        <h1>Profil Artis</h1>
        <nav>
            <a href="home.php">Beranda</a> |
            <a href="upload_music.php">Upload Music</a> |
            <a href="notifications.php">Notifikasi</a> |
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Informasi Artis</h2>
        <p><strong>Nama Artis:</strong> <?= htmlspecialchars($artist['artist_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($artist['email']) ?></p>
        <p><strong>Region:</strong> <?= htmlspecialchars($artist['region']) ?></p>

        <h2>Lagu yang Diunggah</h2>
        <?php if (empty($music_list)): ?>
            <p>Artis ini belum mengunggah musik.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($music_list as $music): ?>
                    <li>
                        <img src="../../public/uploads/images/<?= htmlspecialchars($music['image']) ?>" 
                             alt="Cover <?= htmlspecialchars($music['title']) ?>" 
                             style="width:100px;height:auto;">
                        <a href="play_music.php?id=<?= $music['id_music'] ?>">
                            <?= htmlspecialchars($music['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>

    <?php include '../../music-navbar.php'; ?>
</body>
</html>