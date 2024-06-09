<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8">
    <title>Saveurs Gourmandes</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="musiques.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php" style="display: flex; align-items: center; text-decoration: none;">
                <img src="https://i.pinimg.com/236x/3a/0a/6d/3a0a6d6f756076c888c2ba0e483b34bd.jpg" alt="Logo" style="height: 50px; margin-right: 10px;">
                <span style="font-size: 24px; color: white;">Saveurs Gourmandes</span>
            </a>
        </div>
        <div class="auth-buttons">
            <?php if (isset($_SESSION["is_connected"])): ?>
                <span>Bienvenue, <?= $_SESSION["user_name"] ?> !</span>
                <button onclick="location.href='logout.php'">Se déconnecter</button>
            <?php else: ?>
                <button onclick="location.href='login.php'">Se connecter</button>
                <button onclick="location.href='register.php'">S'inscrire</button>
            <?php endif; ?>
        </div>
        <button onclick="location.href='create.php'">Ajouter une recette</button>
        <button onclick="location.href='help.php'">Besoin d'aide</button>
        <button id="music-button" onclick="toggleMusicMenu()">
            <img src="https://tse4.explicit.bing.net/th?id=OIP.J8HtzX9-U1iYOWJdloAhXAHaHa&pid=Api&P=0&h=180" alt="Music" style="height: 30px;">
        </button>
        <div class="hamburger-icon" onclick="toggleHamburgerMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </header>
    <div id="hamburger-menu" class="hamburger-menu">
        <button onclick="location.href='login.php'">Se connecter</button>
        <button onclick="location.href='register.php'">S'inscrire</button>
        <button onclick="location.href='create.php'">Ajouter une recette</button>
        <button onclick="location.href='help.php'">Besoin d'aide</button>
    </div>
    <iframe id="music-frame" src="audio.php"></iframe>
    <div id="music-menu" class="music-menu">
        <h3>Choisissez une musique :</h3>
        <ul>
            <li onclick="playMusic('audio/ytmp3free.cc_adomirror-youtubemp3free.org.mp3.mp3')">MIRROR / Ado</li>
            <li onclick="playMusic('audio/ytmp3free.cc_billie-eilish-lamour-de-ma-vie-official-lyric-video-youtubemp3free.org.mp3')">L'AMOUR DE MA VIE</li>
            <li onclick="playMusic('audio/music3.mp3')">Arrêter</li>
        </ul>
    </div>
    <script src="header.js"></script>
</body>
</html>
