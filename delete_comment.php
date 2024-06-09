<?php
require("config.php");

if (isset($_GET['id'])) {
    $commentId = $_GET['id'];
    if ($commentsManager->deleteComment($commentId)) {
        header("Location: recipe.php?id=$recipeId");
        exit;
    } else {
        echo "Erreur lors de la suppression du commentaire.";
    }
} else {
    echo "ID du commentaire non spécifié.";
}
?>