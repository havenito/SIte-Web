<?php
require("config.php");

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Aucune recette spécifiée.";
    exit;
}

$recipeId = intval($_GET['id']);
$recipe = $recipesManager->getRecipeById($recipeId);

if (!$recipe || $recipe->user_id != $_SESSION['user_id']) {
    echo "Vous n'avez pas la permission de modifier cette recette.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $ingredients = $_POST['ingredients'];
    $category = $_POST['category'];
    $preparation_time = $_POST['preparation_time'];
    $cooking_time = $_POST['cooking_time'];
    $steps = $_POST['steps'];
    $image = $recipe->image; 

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $image = $uploadFile; 
        } else {
            echo "Une erreur est survenue lors du téléchargement de l'image.";
            exit;
        }
    }

    $stmt = $conn->prepare("UPDATE recipes SET title = ?, ingredients = ?, category = ?, preparation_time = ?, cooking_time = ?, steps = ?, image = ? WHERE id = ?");
    if ($stmt->execute([$title, $ingredients, $category, $preparation_time, $cooking_time, $steps, $image, $recipeId])) {
        echo "La recette a été mise à jour avec succès.";
        header('Location: index.php?id=' . $recipeId);
        exit;
    } else {
        echo "Une erreur est survenue lors de la mise à jour de la recette.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="update.css">
    <title>Modifier la recette</title>
</head>

<body>
    <?php require("header.php"); ?>

    <h1>Modifier la recette</h1>
    <form method="post" action="update.php?id=<?= htmlspecialchars($recipeId) ?>" enctype="multipart/form-data">
        <label for="title">Titre</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($recipe->title) ?>" required>

        <label for="ingredients">Ingrédients</label>
        <textarea id="ingredients" name="ingredients" required><?= htmlspecialchars($recipe->ingredients) ?></textarea>

        <label for="category">Catégorie</label>
        <select name="category" id="category" class="form-control" required>
            <option value="entrée">Entrée</option>
            <option value="plat">Plat</option>
            <option value="dessert">Dessert</option>
        </select><br><br>

        <label for="preparation_time">Temps de préparation</label>
        <input type="text" id="preparation_time" name="preparation_time" value="<?= htmlspecialchars($recipe->preparation_time) ?>" required>

        <label for="cooking_time">Temps de cuisson</label>
        <input type="text" id="cooking_time" name="cooking_time" value="<?= htmlspecialchars($recipe->cooking_time) ?>" required>

        <label for="steps">Étapes</label>
        <textarea id="steps" name="steps" required><?= htmlspecialchars($recipe->steps) ?></textarea>

        <label for="image">Image</label>
        <input type="file" id="image" name="image"><br><br>

        <button type="submit">Mettre à jour</button>
    </form>

    <?php require("footer.php"); ?>
</body>

</html>