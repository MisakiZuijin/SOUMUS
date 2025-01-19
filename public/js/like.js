function likeMusic() {
    const songId = localStorage.getItem('musicId'); // Ambil ID dari LocalStorage
    console.log("ID Musik yang dikirim ke server:", songId); // Debugging ID musik

    if (!songId || isNaN(songId)) { // Validasi jika ID kosong atau bukan angka
        alert('Tidak ada musik yang diputar atau ID tidak valid.');
        return;
    }

    fetch('../../views/user/like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id_music: parseInt(songId, 10), // Pastikan dikirim sebagai angka
        }),
    })
        .then(response => response.json())
        .then(data => {
            console.log("Respons dari server:", data); // Debugging respons server
            if (data.status === 'success') {
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyukai musik.');
        });
}