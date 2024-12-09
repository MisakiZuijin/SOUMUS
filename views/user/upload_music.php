<?php
session_start();
require '../../config/database.php';
require '../../config/functions.php';

if (!is_logged_in()) {
    redirect('../auth/login.php');
}

$user_id = $_SESSION['user_id'];
$message = "";

// Ambil nama artis berdasarkan id_user
$stmt = $pdo->prepare("SELECT artist_name FROM users WHERE id_user = ?");
$stmt->execute([$user_id]);
$artist_name = $stmt->fetchColumn();

// Proses unggah musik
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];
    $license = $_POST['license'];
    $description = $_POST['description'];
    $caption = $_POST['caption'];
    $music_file = $_FILES['music_file']['name'];
    $music_image = $_FILES['music_image']['name'];

    if (!empty($music_file) && !empty($music_image)) {
        // Upload file musik
        $upload_dir_music = '../../public/uploads/music/';
        $upload_file_music = $upload_dir_music . basename($music_file);

        // Upload gambar musik
        $upload_dir_image = '../../public/uploads/images/';
        $upload_file_image = $upload_dir_image . basename($music_image);

        if (move_uploaded_file($_FILES['music_file']['tmp_name'], $upload_file_music) &&
            move_uploaded_file($_FILES['music_image']['tmp_name'], $upload_file_image)) {
            
            // Simpan data musik ke database
            $stmt = $pdo->prepare("INSERT INTO music (title, artist_name, genre, release_date, upload_date, file_name, image, license, description, caption, id_user) 
                                   VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $artist_name, $genre, $release_date, $music_file, $music_image, $license, $description, $caption, $user_id]);

            $music_id = $pdo->lastInsertId();

            // Tambahkan notifikasi untuk pengguna
            $stmt = $pdo->prepare("INSERT INTO notifications (id_user, id_music) VALUES (?, ?)");
            $stmt->execute([$user_id, $music_id]);

            $message = "Musik berhasil diunggah!";
        } else {
            $message = "Gagal mengunggah file musik atau gambar.";
        }
    } else {
        $message = "Harap pilih file musik dan gambar untuk diunggah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Musik</title>
</head>
<body>
    <header>
        <h1>Upload Musik</h1>
        <nav>
            <a href="home.php">Beranda</a> |
            <a href="profile.php">Profil</a> |
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <?php if (!empty($message)): ?>
            <p style="color: green;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Judul:</label>
            <input type="text" name="title" required>
            <br>

            <label>Artis:</label>
            <input type="text" name="artist_name" value="<?= htmlspecialchars($artist_name) ?>" readonly>
            <br>

            <label>Genre:</label>
            <input type="text" name="genre" required>
            <br>

            <label>Tanggal Rilis:</label>
            <input type="date" name="release_date" required>
            <br>

            <label>File Musik:</label>
            <input type="file" name="music_file" accept="audio/*" required>
            <br>

            <label>Gambar Musik:</label>
            <input type="file" name="music_image" accept="image/*" required>
            <br>

            <label>License:</label>
            <select name="license" required>
                <option value="No License">No License</option>
                <option value="Standard License">Standard License</option>
            </select>
            <br>

            <label>Deskripsi:</label>
            <textarea name="description" rows="4" required></textarea>
            <br>

            <label>Caption:</label>
            <textarea name="caption" rows="2"></textarea>
            <br>

            <button type="submit">Unggah Musik</button>
        </form>
    </main>
</body>
</html>
