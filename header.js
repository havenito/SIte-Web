function playMusic(musicFile) {
    const musicFrame = document.getElementById('music-frame');
    musicFrame.contentWindow.postMessage({ type: 'playMusic', file: musicFile }, '*');
}

function toggleMusicMenu() {
    const musicMenu = document.getElementById('music-menu');
    musicMenu.style.display = musicMenu.style.display === 'block' ? 'none' : 'block';
}

function toggleHamburgerMenu() {
    const hamburgerMenu = document.getElementById('hamburger-menu');
    hamburgerMenu.style.display = hamburgerMenu.style.display === 'flex' ? 'none' : 'flex';
}