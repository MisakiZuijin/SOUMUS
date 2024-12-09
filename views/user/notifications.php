<?php
session_start();
require '../../config/database.php';
require '../../config/functions.php';

if (!is_logged_in()) {
    redirect('../auth/login.php');
}

$user_id = $_SESSION['user_id'];

// Ambil daftar notifikasi untuk pengguna
$stmt = $pdo->prepare("
    SELECT n.*, m.title AS music_title, r.content AS reply_content
    FROM notifications n
    LEFT JOIN music m ON n.id_music = m.id_music
    LEFT JOIN replies r ON n.id_reply = r.id_reply
    WHERE n.id_user = ?
    ORDER BY n.id_notification DESC
");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
</head>
<body>
    <header>
        <h1>Notifikasi</h1>
        <nav>
            <a href="home.php">Beranda</a> |
            <a href="upload_music.php">Upload Music</a> |
            <a href="profile.php">Profil</a> |
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Daftar Notifikasi</h2>
        <?php if (empty($notifications)): ?>
            <p>Tidak ada notifikasi saat ini.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($notifications as $notif): ?>
                    <li>
                        <?php if ($notif['id_music']): ?>
                            Musik Anda "<strong><?= htmlspecialchars($notif['music_title']) ?></strong>" telah diupload.
                        <?php elseif ($notif['id_reply']): ?>
                            Anda menerima feedback: "<em><?= htmlspecialchars($notif['reply_content']) ?></em>".
                        <?php else: ?>
                            Notifikasi umum: <?= htmlspecialchars($notif['content'] ?? 'Tidak ada detail.') ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>
</body>
</html>
