<?php
require("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipeId = isset($_GET['id']) ? intval($_GET['id']) : null;
    $userId = $_SESSION['user_id'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];

    $isJsonRecipe = false;
    $recipe = $recipesManager->getRecipeById($recipeId);
    if (!$recipe) {
        $jsonFilePath = 'recipesv1.json'; 
        if (file_exists($jsonFilePath)) {
            $jsonRecipes = json_decode(file_get_contents($jsonFilePath), true);
            foreach ($jsonRecipes as $jsonRecipe) {
                if ($jsonRecipe['id'] == $recipeId) {
                    $isJsonRecipe = true;
                    break;
                }
            }
        }
    }

    if ($isJsonRecipe) {
        echo "Vous ne pouvez pas commenter les recettes par défaut.";
    } else {
        if ($commentsManager->createComment($recipeId, $userId, $comment, $rating)) {
            header("Location: recipe.php?id=$recipeId");
            exit;
        } else {
            echo "Erreur lors de la création du commentaire.";
        }
    }
} else {
    echo "Le formulaire n'a pas été soumis.";
}
?>

