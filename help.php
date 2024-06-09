<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Besoin d'aide</title>
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php">
                <img src="https://i.pinimg.com/236x/3a/0a/6d/3a0a6d6f756076c888c2ba0e483b34bd.jpg" alt="Logo">
            </a>
            <span>Saveurs Gourmandes</span>
        </div>
    </header>
    <main>
        <h1>Besoin d'aide ?</h1>
        <form action="https://formspree.io/f/xkndalbj" method="POST">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required placeholder="Votre Nom">
            <br>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required placeholder="Votre Email">
            <br>

            <label for="message">Message :</label>
            <textarea id="message" name="message" rows="5" required placeholder="Votre message"></textarea>
            <br><br>

            <input type="submit" value="Envoyer">
        </form>
    </main>
</body>

<?php
require("header.php");
?>

</html>