<?php
session_start();
require '../../config/database.php'; // Koneksi database

if (!isset($_SESSION['user_id'])) {
    error_log("Sesi tidak ditemukan atau pengguna belum login.");
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login untuk menyukai musik.']);
    exit;
}

$user_id = $_SESSION['user_id'];
error_log("ID User yang digunakan: $user_id");

if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
    $json = file_get_contents('php://input');
    $_POST = json_decode($json, true);
    error_log("Data JSON diterima: " . json_encode($_POST)); // Log data JSON
}

$id_music = $_POST['id_music'] ?? null;

// Log data yang diterima
error_log("ID Musik yang diterima dari JSON: " . $id_music);

// Validasi data
if (empty($id_music) || !is_numeric($id_music)) {
    error_log("ID Musik tidak valid: " . $id_music); // Log kesalahan
    echo json_encode(['status' => 'error', 'message' => 'ID musik tidak valid.']);
    exit;
}

$id_music = intval($id_music); // Konversi ke integer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_music = $_POST['id_music'] ?? null;

    // Validasi id_music
    if (empty($id_music)) {
        echo json_encode(['status' => 'error', 'message' => 'ID musik tidak ditemukan.']);
        exit;
    }

    if (!is_numeric($id_music)) {
        echo json_encode(['status' => 'error', 'message' => 'ID musik tidak valid.']);
        exit;
    }

    $id_music = intval($id_music);

    try {
        error_log("Proses like dimulai untuk music_id: $id_music, user_id: $user_id");

        $stmt = $pdo->prepare("SELECT 1 FROM likes WHERE id_music = ? AND id_user = ?");
        $stmt->execute([$id_music, $user_id]);

        if ($stmt->fetchColumn()) {
            echo json_encode(['status' => 'error', 'message' => 'Anda sudah menyukai musik ini.']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO likes (id_music, id_user) VALUES (?, ?)");
        $stmt->execute([$id_music, $user_id]);

        $stmt = $pdo->prepare("UPDATE music SET like_count = like_count + 1 WHERE id_music = ?");
        $stmt->execute([$id_music]);

        echo json_encode(['status' => 'success', 'message' => 'Musik berhasil disukai.']);
    } catch (Exception $e) {
        error_log("Kesalahan SQL: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Kesalahan saat memproses like.', 'details' => $e->getMessage()]);
    }
}
