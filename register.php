<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="h1.css">
    <title>Document</title>
</head>

<body>


<?php
require("config.php");
require("header.php");

if ($_POST) {
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($usersManager->createUser($firstName, $lastName, $email, $password)) {
        echo "<script>alert('Compte créé avec succès.'); window.location.href='login.php';</script>";
    } else {
        echo "<p style='color: red; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background-color: #ff5722; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); font-size: 16px; font-weight: bold; z-index: 1001;'>Erreur lors de la création du compte. Veuillez réessayer.</p>";
    }
}
?>
<h1 class="mt-2">Créer un compte utilisateur</h1>
<form method="post">
    <label for="firstName">Prénom</label>
    <input type="text" name="firstName" id="firstName" placeholder="Votre prénom" class="form-control" required>
    <label for="lastName">Nom</label>
    <input type="text" name="lastName" id="lastName" placeholder="Votre nom" class="form-control" required>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Votre adresse e-mail" class="form-control" required>
    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password" placeholder="Créer un mot de passe" class="form-control" required minlength=6 maxlength=30>
    <input type="submit" value="S'inscrire" class="mt-2 btn btn-primary">
</form>
<a href="login.php">Déjà inscrit ? Se connecter</a>

<?php require("footer.php"); ?>

</body>

</html>