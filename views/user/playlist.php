<?php
session_start();
require '../../config/functions.php';

if (!is_logged_in()) {
    redirect('../auth/login.php');
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
    <h1>Halaman Playlist</h1>
    <a href="home.php">Kembali ke Beranda</a>
    <?php include '../../music-navbar.php'; ?>
</body>
</html>
