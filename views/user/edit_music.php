<?php
session_start();
require '../../config/database.php';
require '../../config/functions.php';

if (!is_logged_in()) {
    redirect('../auth/login.php');
}

$user_id = $_SESSION['user_id'];

// Ambil ID musik dari URL
if (!isset($_GET['id'])) {
    die("ID musik tidak diberikan.");
}

$music_id = $_GET['id'];

// Ambil data musik berdasarkan ID dan pastikan musik milik pengguna
$stmt = $pdo->prepare("SELECT * FROM music WHERE id_music = ? AND id_user = ?");
$stmt->execute([$music_id, $user_id]);
$music = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$music) {
    die("Musik tidak ditemukan atau Anda tidak memiliki izin untuk mengeditnya.");
}

// Proses pembaruan musik
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];
    $license = $_POST['license'];
    $description = $_POST['description'];
    $caption = $_POST['caption'];

    $music_image = $_FILES['music_image']['name'] ?? '';
    $music_file = $_FILES['music_file']['name'] ?? '';

    // Upload file baru jika ada
    if (!empty($music_file)) {
        $upload_dir_music = '../../public/uploads/music/';
        $upload_file_music = $upload_dir_music . basename($music_file);
        move_uploaded_file($_FILES['music_file']['tmp_name'], $upload_file_music);
    } else {
        $music_file = $music['file_name']; // Gunakan file lama jika tidak diubah
    }

    if (!empty($music_image)) {
        $upload_dir_image = '../../public/uploads/images/';
        $upload_file_image = $upload_dir_image . basename($music_image);
        move_uploaded_file($_FILES['music_image']['tmp_name'], $upload_file_image);
    } else {
        $music_image = $music['image']; // Gunakan gambar lama jika tidak diubah
    }

    // Update data musik ke database
    $stmt = $pdo->prepare("UPDATE music SET 
        title = ?, 
        genre = ?, 
        release_date = ?, 
        license = ?, 
        description = ?, 
        caption = ?, 
        file_name = ?, 
        image = ? 
        WHERE id_music = ? AND id_user = ?");
    $stmt->execute([$title, $genre, $release_date, $license, $description, $caption, $music_file, $music_image, $music_id, $user_id]);

    // Redirect setelah berhasil memperbarui
    redirect('profile.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Musik</title>
</head>
<body>
    <header>
        <h1>Edit Musik</h1>
        <nav>
            <a href="profile.php">Profil</a> |
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <form method="POST" enctype="multipart/form-data">
            <label>Judul:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($music['title']) ?>" required>
            <br>

            <label>Genre:</label>
            <input type="text" name="genre" value="<?= htmlspecialchars($music['genre']) ?>" required>
            <br>

            <label>Tanggal Rilis:</label>
            <input type="date" name="release_date" value="<?= htmlspecialchars($music['release_date']) ?>" required>
            <br>

            <label>License:</label>
            <select name="license" required>
                <option value="No License" <?= $music['license'] === 'No License' ? 'selected' : '' ?>>No License</option>
                <option value="Standard License" <?= $music['license'] === 'Standard License' ? 'selected' : '' ?>>Standard License</option>
            </select>
            <br>

            <label>Deskripsi:</label>
            <textarea name="description" rows="4" required><?= htmlspecialchars($music['description']) ?></textarea>
            <br>

            <label>Caption:</label>
            <textarea name="caption" rows="2"><?= htmlspecialchars($music['caption']) ?></textarea>
            <br>

            <label>File Musik:</label>
            <input type="file" name="music_file" accept="audio/*">
            <br>

            <label>Gambar Musik:</label>
            <?php if (!empty($music['image'])): ?>
                <img src="../../public/uploads/images/<?= htmlspecialchars($music['image']) ?>" alt="Cover <?= htmlspecialchars($music['title']) ?>" width="100">
            <?php endif; ?>
            <input type="file" name="music_image" accept="image/*">
            <br>

            <button type="submit">Perbarui Musik</button>
        </form>
    </main>
</body>
</html>
