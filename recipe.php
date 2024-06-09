<?php
require("config.php");
require("header.php");

$recipeId = isset($_GET['id']) ? intval($_GET['id']) : null;
$recipe = null;
$isJsonRecipe = false;

if ($recipeId) {
    $recipe = $recipesManager->getRecipeById($recipeId);
    if (!$recipe) {
        $jsonFilePath = 'recipesv1.json'; 
        if (file_exists($jsonFilePath)) {
            $jsonRecipes = json_decode(file_get_contents($jsonFilePath), true);
            foreach ($jsonRecipes as $jsonRecipe) {
                if ($jsonRecipe['id'] == $recipeId) {
                    $recipe = (object) $jsonRecipe;
                    $isJsonRecipe = true;
                    break;
                }
            }
        }
    }

    if ($recipe) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comment"]) && isset($_SESSION["user_id"]) && (!$isJsonRecipe || (isset($recipe->user_id) && $_SESSION["user_id"] != $recipe->user_id))) {
            $comment = $_POST["comment"];
            $rating = $_POST["rating"];
            $commentsManager->createComment($recipeId, $_SESSION["user_id"], $comment, $rating);
            header("Location: recipe.php?id=$recipeId");
            exit;
        }

        $comments = $commentsManager->getCommentsByRecipe($recipeId);
    } else {
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}

$title = htmlspecialchars($recipe->title ?? 'Titre non disponible');
$category = htmlspecialchars($recipe->category ?? 'Catégorie non disponible');
$preparation_time = htmlspecialchars($recipe->preparation_time ?? 'Temps de préparation non disponible');
$cooking_time = htmlspecialchars($recipe->cooking_time ?? 'Temps de cuisson non disponible');
$ingredients = htmlspecialchars($recipe->ingredients ?? 'Ingrédients non disponibles');
$steps = nl2br(htmlspecialchars($recipe->steps ?? 'Instructions non disponibles'));
$image = htmlspecialchars($recipe->image ?? 'path/to/default/image.jpg');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="comment.css">
    <link rel="stylesheet" href="recipe.css">
    <title>Recette</title>
</head>

<body>
    <div class="content">
        <div class="recipe-details">
            <div class="recipe-image">
                <h1><u><?= $title ?></u></h1>
                <img src="<?= $image ?>" alt="Image de la recette">
            </div>
            <div class="recipe-info">
                <span class="prout">Temps de préparation : <?= $preparation_time ?><span><br><br>
                        <span class="prout">Temps de cuisson : <?= $cooking_time ?></span><br><br>
                        <span class="prout">Ingrédients :</span>
                        <ul>
                            <?php
                            $ingredientsList = array_filter(array_map('trim', explode("\n", $ingredients)));
                            foreach ($ingredientsList as $ingredient) : ?>
                                <li><?= htmlspecialchars($ingredient) ?></li>
                            <?php endforeach; ?>
                        </ul>
            </div>
        </div>
        <br>
        <hr>
    </div>
    <div class="instructions">
        <h2>Instructions :</h2>
        <p><?= $steps ?></p>
    </div>
    </div>

    <?php if (isset($_SESSION["user_id"])) : ?>
        <?php if (!$isJsonRecipe && ((isset($recipe->user_id) && $_SESSION["user_id"] != $recipe->user_id))) : ?>
            <h2>Ajouter un commentaire :</h2>
            <form method="post" action="recipe.php?id=<?= $recipeId ?>">
                <textarea name="comment" placeholder="Votre commentaire :" required></textarea>
                <label for="rating">Note :</label>
                <select name="rating" id="rating">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select><br><br>
                <input type="submit" value="Ajouter commentaire">
            </form>
        <?php elseif ($isJsonRecipe): ?>
            <p>Vous ne pouvez pas commenter les recettes par défaut.</p>
        <?php else : ?>
            <p>Vous ne pouvez pas commenter votre propre recette.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($comments) : ?>
        <h2>Commentaires :</h2>
        <?php foreach ($comments as $comment) : ?>
            <div class="comment">
                <p><strong><?= htmlspecialchars($comment->firstName . ' ' . $comment->lastName) ?>:</strong> <?= htmlspecialchars($comment->comment) ?></p>
                <p>Note : <?= htmlspecialchars($comment->rating) ?></p>
                <?php if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == $comment->user_id) : ?>
                    <a href="update_comment.php?id=<?= $comment->id ?>">Modifier</a>
                    <a href="delete_comment.php?id=<?= $comment->id ?>">Supprimer</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Aucun commentaire disponible pour cette recette.</p>
    <?php endif; ?>

    <?php require("footer.php"); ?>
</body>

</html>

