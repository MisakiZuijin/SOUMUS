<div id="music-navbar" style="position: fixed; bottom: 0; left: 0; width: 100%; background: #333; color: #fff; padding: 10px; z-index: 999;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <strong id="music-title" >Tidak ada musik yang diputar</strong>
        </div>
        <div>
            <audio id="music-player" controls style="width: 300px;">
                <source id="music-source" src="" type="audio/mpeg">
                Browser Anda tidak mendukung pemutar audio.
            </audio>
        </div>
        <button onclick="likeMusic()" id="like-button" style="background: #00f; color: #fff; border: none; padding: 5px 10px; margin-right: 10px; cursor: pointer;">
            Like ❤️
        </button>
        <button onclick="stopMusic()" style="background: #f00; color: #fff; border: none; padding: 5px 10px; margin-right: 20px;  cursor: pointer;">
            Stop
        </button>
    </div>
</div>

<script>
    const musicPlayer = document.getElementById('music-player');
    const musicTitleElement = document.getElementById('music-title');
    const musicSourceElement = document.getElementById('music-source');

    musicPlayer.addEventListener('volumechange', () => {
        const currentVolume = musicPlayer.volume;
        localStorage.setItem('musicVolume', currentVolume);
        console.log("Volume disimpan:", currentVolume);
    });

    musicPlayer.addEventListener('play', () => {
        localStorage.setItem('musicPaused', false); // Lagu sedang dimainkan
        console.log("Status pemutar: Playing");
    });

    musicPlayer.addEventListener('pause', () => {
        localStorage.setItem('musicPaused', true); // Lagu sedang dijeda
        console.log("Status pemutar: Paused");
    });

    function playMusic(title, file, id) {
    console.log("Memutar musik dengan ID:", id); // Debugging

    if (!id || isNaN(id)) {
        console.error("ID Musik tidak valid:", id); // Log jika ID tidak valid
        return;
    }

    // Simpan detail musik ke localStorage
    localStorage.setItem('musicTitle', title);
    localStorage.setItem('musicFile', file);
    localStorage.setItem('musicId', id); // Simpan ID musik
    localStorage.setItem('musicTime', 0);

    // Perbarui tampilan dan audio
    loadMusic();
    }

    function stopMusic() {
        localStorage.removeItem('musicTitle');
        localStorage.removeItem('musicFile');
        localStorage.removeItem('musicTime');
        localStorage.removeItem('musicVolume');
        loadMusic();
    }

    
    function loadMusic() {
        const title = localStorage.getItem('musicTitle');
        const file = localStorage.getItem('musicFile');
        const savedTime = parseFloat(localStorage.getItem('musicTime')) || 0;
        const savedVolume = parseFloat(localStorage.getItem('musicVolume'));
        const isPaused = localStorage.getItem('musicPaused') === 'true'; // Ambil status paused

        if (title && file) {
            musicTitleElement.textContent = title;
            musicSourceElement.src = file;

            musicPlayer.load();

            musicPlayer.addEventListener('loadedmetadata', () => {
                musicPlayer.currentTime = savedTime;

                if (!isNaN(savedVolume)) {
                    musicPlayer.volume = savedVolume; // Setel volume yang disimpan
                }

                if (!isPaused) {
                    musicPlayer.play(); // Mainkan jika statusnya "Playing"
                    console.log("Status pemutar: Playing");
                } else {
                    console.log("Status pemutar: Paused");
                }
            });

            musicPlayer.addEventListener('timeupdate', () => {
                localStorage.setItem('musicTime', musicPlayer.currentTime);
            });
        } else {
            musicTitleElement.textContent = 'Tidak ada musik yang diputar';
            musicSourceElement.src = '';
            musicPlayer.load();
        }
    }

    // Panggil loadMusic setiap kali halaman dimuat
    document.addEventListener('DOMContentLoaded', loadMusic);
</script>
