<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="h1.css">
    <title>Créer recette</title>
</head>

<body>

<h1 class="mt-2">Ajouter une nouvelle recette</h1>
<form method="post" enctype="multipart/form-data">
    <label for="title">Nom du plat</label>
    <input type="text" name="title" id="title" placeholder="Nom du plat" class="form-control" required>

    <label for="ingredients">Ingrédients</label>
    <textarea name="ingredients" id="ingredients" placeholder="Ingrédients" class="form-control" required></textarea>
<br><br>
    <label for="category">Catégorie</label>
    <select name="category" id="category" class="form-control" required>
        <option value="entrée">Entrée</option>
        <option value="plat">Plat</option>
        <option value="dessert">Dessert</option>
    </select><br><br>

    <label for="preparation_time">Temps de préparation</label>
    <input type="text" name="preparation_time" id="preparation_time" placeholder="Temps de préparation" class="form-control">

    <label for="cooking_time">Temps de cuisson</label>
    <input type="text" name="cooking_time" id="cooking_time" placeholder="Temps de cuisson" class="form-control">

    <label for="steps">Étapes</label>
    <textarea name="steps" id="steps" placeholder="Étapes de la recette" class="form-control" required></textarea><br><br>

    <label for="image">Image du plat (400*400)</label>
    <input type="file" name="image" id="image" class="form-control"> <br><br>

    <input type="submit" value="Ajouter" class="mt-2 btn btn-primary">
</form>

<?php require("footer.php"); ?>

<?php
require("config.php");
require("header.php");

if (!isset($_SESSION["is_connected"])) {
    echo "<script>alert('Vous devez être connecté pour ajouter une recette.'); window.location.href='login.php';</script>";
    exit;
}

if ($_POST) {
    $userId = $_SESSION["user_id"];
    $title = $_POST["title"];
    $ingredients = $_POST["ingredients"];
    $category = $_POST["category"];
    $preparation_time = $_POST["preparation_time"];
    $cooking_time = $_POST["cooking_time"];
    $steps = $_POST["steps"];

    $imagePath = "path/to/default/image.JPG";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileInfo = pathinfo($_FILES['image']['name']);
        $fileExtension = strtolower($fileInfo['extension']);

        if (in_array($fileExtension, $allowedExtensions)) {
            $targetDirectory = 'uploads/';
            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }
            $targetFile = $targetDirectory . uniqid() . '.' . $fileExtension;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = $targetFile;
            } else {
                echo "<p style='color: red;'>Erreur lors du téléchargement de l'image.</p>";
            }
        } else {
            echo "<p style='color: red;'>Extension de fichier non autorisée.</p>";
        }
    }

    if ($recipesManager->createRecipe($userId, $title, $ingredients, $category, $preparation_time, $cooking_time, $steps, $imagePath)) {
        echo "<script>alert('Recette ajoutée avec succès.'); window.location.href='index.php';</script>";
    } else {
        echo "<p style='color: red; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background-color: #ff5722; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); font-size: 16px; font-weight: bold; z-index: 1001;'>Erreur lors de l'ajout de la recette. Veuillez réessayer.</p>";
    }
}
?>
</body>

</html>