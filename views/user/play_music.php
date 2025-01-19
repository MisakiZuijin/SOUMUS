<?php
session_start();
require '../../config/database.php';
require '../../config/functions.php';

if (!is_logged_in()) {
    redirect('../auth/login.php');
}

if (isset($_GET['id'])) {
    $id_music = intval($_GET['id']); // Pastikan ID selalu berupa integer
    error_log("ID Musik yang diterima: " . $id_music); // Debugging

    $stmt = $pdo->prepare("SELECT * FROM music WHERE id_music = ?");
    $stmt->execute([$id_music]);
    $music = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$music) {
        die("Musik tidak ditemukan.");
    }
} else {
    die("ID musik tidak diberikan.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play Music</title>
</head>
<body>
    <header>
        <h1>Play Music</h1>
        <nav>
            <a href="home.php">Beranda</a> |
            <a href="upload_music.php">Upload Music</a> |
            <a href="profile.php">Profil</a> |
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Memutar: <?= htmlspecialchars($music['title']) ?></h2>
        <p>Artis: <?= htmlspecialchars($music['artist']) ?></p>
        <button onclick="playMusic(
            '<?= addslashes($music['title']) ?>',
            '../../public/uploads/music/<?= addslashes($music['file_name']) ?>',
            <?= intval($music['id_music']) ?>)">
            Putar Musik
        </button>
    </main>

    <?php include '../../music-navbar.php'; ?>
</body>
</html>
