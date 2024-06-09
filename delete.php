<?php
require("config.php");

if (!isset($_SESSION["is_connected"])) {
    echo "<script>alert('Vous devez être connecté pour supprimer une recette.'); window.location.href='login.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $recipeId = $_GET['id'];

    $stmt = $conn->prepare("SELECT user_id FROM recipes WHERE id = ?");
    $stmt->execute([$recipeId]);
    $recipe = $stmt->fetch(PDO::FETCH_OBJ);

    if ($recipe && $recipe->user_id == $_SESSION["user_id"]) {
        $conn->beginTransaction();

        try {
            $stmt = $conn->prepare("DELETE FROM comments WHERE recipe_id = ?");
            $stmt->execute([$recipeId]);

            $stmt = $conn->prepare("DELETE FROM recipes WHERE id = ?");
            $stmt->execute([$recipeId]);

            $conn->commit();

            echo "<script>alert('Recette supprimée avec succès.'); window.location.href='index.php';</script>";
        } catch (Exception $e) {
            $conn->rollBack();
            echo "<script>alert('Erreur lors de la suppression de la recette.'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('Vous n\'êtes pas autorisé à supprimer cette recette.'); window.location.href='index.php';</script>";
    }
} else {
    echo "<script>alert('Aucune recette spécifiée.'); window.location.href='index.php';</script>";
}
?>
