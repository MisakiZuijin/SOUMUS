<?php
session_start();
require '../../config/database.php';
require '../../config/functions.php';

if (!is_logged_in()) {
    redirect('../auth/login.php');
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <style>
        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 15px;
        }

        img {
            vertical-align: middle;
            margin-right: 10px;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header>
        <h1>Selamat Datang, <?= htmlspecialchars($username) ?>!</h1>
        <nav>
            <a href="playlist.php">Playlist</a> |
            <a href="profile.php">Profil</a> |
            <a href="upload_music.php">Upload Music</a> |
            <a href="feed.php">FeedBack</a> |
            <a href="notifications.php">Notifications</a> |
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Musik Terbaru</h2>
        <ul>
            <?php
            try {
                // Query untuk mengambil musik terbaru dengan informasi artis
                $stmt = $pdo->query("
                    SELECT m.id_music, m.title, m.upload_date, m.image, u.id_user, u.artist_name 
                    FROM music m 
                    JOIN users u ON m.id_user = u.id_user 
                    ORDER BY m.upload_date DESC 
                    LIMIT 10
                ");

                if ($stmt->rowCount() > 0) {
                    while ($music = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <li>
                            <img src="../../public/uploads/images/<?= htmlspecialchars($music['image']) ?>" 
                                alt="Cover <?= htmlspecialchars($music['title']) ?>" 
                                style="width:100px;height:auto;">
                            <a href="play_music.php?id=<?= $music['id_music'] ?>">
                                <?= htmlspecialchars($music['title']) ?>
                            </a> - 
                            <a href="artist_profile.php?id=<?= $music['id_user'] ?>">
                                <?= htmlspecialchars($music['artist_name']) ?>
                            </a>
                        </li>
                    <?php endwhile;
                } else {
                    echo "<p>Tidak ada musik yang tersedia saat ini.</p>";
                }
            } catch (PDOException $e) {
                echo "<p>Terjadi kesalahan: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </ul>
    </main>

    <?php include '../../music-navbar.php'; ?>
</body>
</html>