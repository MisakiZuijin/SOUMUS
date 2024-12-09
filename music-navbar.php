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
        <button onclick="stopMusic()" style="background: #f00; color: #fff; border: none; padding: 5px 10px; margin-right: 20px;  cursor: pointer;">
            Stop
        </button>
    </div>
</div>

<script>
    const musicPlayer = document.getElementById('music-player');
    const musicTitleElement = document.getElementById('music-title');
    const musicSourceElement = document.getElementById('music-source');

    function playMusic(title, file) {
        localStorage.setItem('musicTitle', title);
        localStorage.setItem('musicFile', file);
        localStorage.setItem('musicTime', 0); // Mulai dari awal
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
        const savedVolume = parseFloat(localStorage.getItem('musicVolume')) || 1; // Default volume 100%

        if (title && file) {
            musicTitleElement.textContent = title;
            musicSourceElement.src = file;

            musicPlayer.load();

            // Tunggu hingga file siap untuk dimainkan
            musicPlayer.addEventListener('loadedmetadata', () => {
                musicPlayer.currentTime = savedTime; // Lanjutkan dari waktu terakhir
                musicPlayer.volume = savedVolume; // Setel volume terakhir
                musicPlayer.play();
            });

            // Simpan waktu pemutaran terakhir setiap detik
            musicPlayer.addEventListener('timeupdate', () => {
                localStorage.setItem('musicTime', musicPlayer.currentTime);
            });

            // Simpan pengaturan volume setiap kali berubah
            musicPlayer.addEventListener('volumechange', () => {
                localStorage.setItem('musicVolume', musicPlayer.volume);
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
