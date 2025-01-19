<?php
// Fungsi utilitas
function redirect($url) {
    header("Location: $url");
    exit;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Fungsi untuk playlist
function create_playlist($pdo, $name, $user_id) {
    if (empty($name)) {
        return "Nama playlist tidak boleh kosong.";
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO playlists (name, id_user) VALUES (?, ?)");
        $stmt->execute([$name, $user_id]); // Masukkan $user_id ke kolom id_user
        return true;
    } catch (PDOException $e) {
        return "Terjadi kesalahan saat membuat playlist: " . $e->getMessage();
    }
}

function get_playlists($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM playlists WHERE id_user = ? ORDER BY name ASC");
        $stmt->execute([$user_id]); // Gunakan $user_id untuk mencocokkan dengan id_user
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

function get_playlist_details($pdo, $playlist_id) {
    try {
        $stmt = $pdo->prepare("
            SELECT dp.id_playlist, m.title, m.artist_name, m.upload_date
            FROM playlist_details AS dp
            JOIN music AS m ON dp.id_music = m.id_music
            WHERE dp.id_playlist = ?
        ");
        $stmt->execute([$playlist_id]);
        $details = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Playlist Details: " . json_encode($details)); // Log hasil query
        return $details;
    } catch (PDOException $e) {
        error_log("Error fetching playlist details: " . $e->getMessage());
        return false;
    }
}

function add_song_to_playlist($pdo, $playlist_id, $music_id) {
    try {
        $stmt = $pdo->prepare("INSERT INTO detail_playlist (playlist_id, music_id) VALUES (?, ?)");
        $stmt->execute([$playlist_id, $music_id]);
        return true;
    } catch (PDOException $e) {
        return "Terjadi kesalahan saat menambahkan lagu ke playlist: " . $e->getMessage();
    }
}

function delete_playlist($pdo, $playlist_id, $user_id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM playlists WHERE id_playlist = ? AND id_user = ?");
        $stmt->execute([$playlist_id, $user_id]); // Gunakan $user_id untuk mencocokkan dengan id_user
        return true;
    } catch (PDOException $e) {
        return "Gagal menghapus playlist: " . $e->getMessage();
    }
}
?>
