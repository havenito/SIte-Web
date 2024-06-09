<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="h1.css">
    <title>Se connecter</title>
</head>

<body>



<?php
require("config.php");
require("header.php");

if ($_POST) {
    $user = $usersManager->readByEmail($_POST["email"]);

    if ($user && password_verify($_POST["password"], $user->password)) {
        $_SESSION["is_connected"] = $user->email;
        $_SESSION["user_name"] = $user->firstName . " " . $user->lastName;
        $_SESSION["user_id"] = $user->id;
        echo "<script>window.location.href='index.php'</script>";
    } else {
        echo "<p style='color: red; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background-color: #ff5722; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); font-size: 16px; font-weight: bold; z-index: 1001;'>Email ou mot de passe incorrect.</p>";
    }
}
?>
<h1 class="mt-2">Connexion utilisateur</h1>

<form method="post">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Votre adresse e-mail" class="form-control" required>
    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password" placeholder="Votre mot de passe" class="form-control" required minlength=6 maxlength=30>
    <input type="submit" value="Se connecter" class="mt-2 btn btn-primary">
</form>
<a href="register.php">Créer un compte utilisateur</a>

<?php require("footer.php"); ?>

</body>

</html>