<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <script>
        // Fungsi untuk menghentikan musik sebelum redirect
        function stopMusic() {
            localStorage.removeItem('musicTitle');
            localStorage.removeItem('musicFile');
            localStorage.removeItem('musicTime');
            localStorage.removeItem('musicVolume');
        }

        // Panggil fungsi stopMusic dan redirect ke login
        function logoutAndRedirect() {
            stopMusic(); // Hentikan musik
            window.location.href = "login.php"; // Redirect ke halaman login
        }
    </script>
</head>
<body onload="logoutAndRedirect()">
    <!-- Pesan Logout -->
    <h1>Logout Berhasil...</h1>
</body>
</html>
