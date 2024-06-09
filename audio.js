function playMusic(musicFile) {
    const audioSource = document.getElementById('audio-source');
    const audioPlayer = document.getElementById('audio-player');
    audioSource.src = musicFile;
    audioPlayer.load();
    audioPlayer.play();
    audioPlayer.currentTime = localStorage.getItem('currentTime') || 0;
}

window.addEventListener('message', (event) => {
    if (event.data.type === 'playMusic') {
        playMusic(event.data.file);
        localStorage.setItem('currentMusic', event.data.file);
    }
});

const audioPlayer = document.getElementById('audio-player');
audioPlayer.addEventListener('timeupdate', () => {
    localStorage.setItem('currentTime', audioPlayer.currentTime);
});

const currentMusic = localStorage.getItem('currentMusic');
if (currentMusic) {
    playMusic(currentMusic);
}