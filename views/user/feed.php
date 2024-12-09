<?php
session_start();
require '../../config/database.php';
require '../../config/functions.php';

if (!is_logged_in()) {
    redirect('../auth/login.php');
}

$user_id = $_SESSION['user_id'];
$message = "";

// Proses penambahan feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $option = $_POST['option_feed'];
    $content = $_POST['content'];

    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO feeds (id_user, option_feed, content) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $option, $content]);
        $message = "Feedback berhasil dikirim!";
    } else {
        $message = "Harap isi konten feedback.";
    }
}

// Ambil daftar feedback dari pengguna
$stmt = $pdo->prepare("SELECT * FROM feeds WHERE id_user = ? ORDER BY id_feed DESC");
$stmt->execute([$user_id]);
$feeds = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed</title>
</head>
<body>
    <header>
        <h1>Feed</h1>
        <nav>
            <a href="home.php">Beranda</a> |
            <a href="notifications.php">Notifikasi</a> |
            <a href="profile.php">Profil</a> |
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Kirim Feedback</h2>
        <?php if (!empty($message)): ?>
            <p style="color: green;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="option_feed">Tipe Feedback:</label>
            <select name="option_feed" id="option_feed" required>
                <option value="FeedBack">FeedBack</option>
                <option value="Report">Report</option>
            </select>
            <br>

            <label for="content">Konten:</label>
            <textarea name="content" id="content" rows="4" required></textarea>
            <br>

            <button type="submit">Kirim</button>
        </form>

        <h2>Feedback Anda</h2>
        <?php if (empty($feeds)): ?>
            <p>Anda belum mengirimkan feedback.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($feeds as $feed): ?>
                    <li>
                        <strong><?= htmlspecialchars($feed['option_feed']) ?>:</strong>
                        <?= htmlspecialchars($feed['content']) ?>
                        <small>(ID Feed: <?= htmlspecialchars($feed['id_feed']) ?>)</small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>
</body>
</html>
