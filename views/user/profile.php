<?php
session_start();
require '../../config/database.php';
require '../../config/functions.php';

if (!is_logged_in()) {
    redirect('../auth/login.php');
}

$user_id = $_SESSION['user_id'];

// Ambil data user dari database
$stmt = $pdo->prepare("SELECT username, email, date_of_birth, photo, artist_name, phone_number, region, gender FROM users WHERE id_user = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT id_music, title, image FROM music WHERE id_user = ?");
$stmt->execute([$user_id]);
$music_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Proses pembaruan profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $date_of_birth = $_POST['date_of_birth'];
    $artist_name = $_POST['artist_name'];
    $phone_number = $_POST['phone_number'];
    $region = $_POST['region'];
    $gender = $_POST['gender'];
    $photo = $_FILES['photo']['name'];

    // Upload foto jika ada
    if (!empty($photo)) {
        $upload_dir = '../../public/uploads/';
        $upload_file = $upload_dir . basename($photo);
        move_uploaded_file($_FILES['photo']['tmp_name'], $upload_file);

        // Update data dengan foto baru
        $stmt = $pdo->prepare("UPDATE users SET email = ?, date_of_birth = ?, artist_name = ?, phone_number = ?, region = ?, gender = ?, photo = ? WHERE id_user = ?");
        $stmt->execute([$email, $date_of_birth, $artist_name, $phone_number, $region, $gender, $photo, $user_id]);
    } else {
        // Update data tanpa mengganti foto
        $stmt = $pdo->prepare("UPDATE users SET email = ?, date_of_birth = ?, artist_name = ?, phone_number = ?, region = ?, gender = ? WHERE id_user = ?");
        $stmt->execute([$email, $date_of_birth, $artist_name, $phone_number, $region, $gender, $user_id]);
    }

    // Redirect untuk menghindari submit ulang
    redirect('profile.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
</head>
<body>
    <header>
        <h1>Profil Anda</h1>
        <nav>
            <a href="home.php">Beranda</a> |
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Informasi Profil</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Username:</label>
            <input type="text" value="<?= htmlspecialchars($user['username']) ?>" readonly>
            <br>

            <label>Nama Artis:</label>
            <input type="text" name="artist_name" value="<?= htmlspecialchars($user['artist_name']) ?>" required>
            <br>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <br>

            <label>Tanggal Lahir:</label>
            <input type="date" name="date_of_birth" value="<?= htmlspecialchars($user['date_of_birth']) ?>">
            <br>

            <label>Nomor Telepon:</label>
            <input type="text" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" required>
            <br>

            <label>Region:</label>
            <input type="text" name="region" value="<?= htmlspecialchars($user['region']) ?>" required>
            <br>

            <label>Gender:</label>
            <select name="gender" required>
                <option value="Male" <?= $user['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $user['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $user['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
            <br>

            <label>Foto Profil:</label>
            <?php if (!empty($user['photo'])): ?>
                <img src="../../public/uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Foto Profil" width="100">
            <?php endif; ?>
            <input type="file" name="photo">
            <br>

            <button type="submit">Perbarui Profil</button>
        </form>
    </main>

    <h2>Musik yang Diunggah</h2>
    <?php if (empty($music_list)): ?>
        <p>Anda belum mengunggah musik.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($music_list as $music): ?>
                <li>
                    <?php if (!empty($music['image'])): ?>
                        <img src="../../public/uploads/images/<?= htmlspecialchars($music['image']) ?>" 
                             alt="Cover <?= htmlspecialchars($music['title']) ?>" 
                             style="width:100px;height:auto;">
                    <?php endif; ?>
                    <?= htmlspecialchars($music['title']) ?> 
                    <a href="edit_music.php?id=<?= $music['id_music'] ?>" style="color: blue; text-decoration: underline;">Edit</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php include '../../music-navbar.php'; ?>
</body>
</html>