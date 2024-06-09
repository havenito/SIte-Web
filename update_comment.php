<?php
require("config.php");
require("header.php");

if (!isset($_SESSION["is_connected"])) {
    echo "<script>alert('Vous devez être connecté pour modifier un commentaire.'); window.location.href='login.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $commentId = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM comments WHERE id = ?");
    $stmt->execute([$commentId]);
    $comment = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$comment || $comment->user_id != $_SESSION["user_id"]) {
        echo "<script>alert('Vous n\'êtes pas autorisé à modifier ce commentaire.'); window.location.href='index.php';</script>";
        exit;
    }

    $recipeId = $comment->recipe_id;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comment"])) {
        $commentText = $_POST["comment"];
        $rating = $_POST["rating"];

        if ($commentsManager->updateComment($commentId, $commentText, $rating)) {
            header("Location: recipe.php?id=$recipeId");
            exit;
        } else {
            echo "Erreur lors de la mise à jour du commentaire.";
        }
    }
} else {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Modifier le Commentaire</title>
</head>

<body>
    <h1>Modifier le Commentaire</h1>
    <form method="post">
        <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment->id ?? '') ?>">
        <input type="hidden" name="recipe_id" value="<?= htmlspecialchars($recipeId ?? '') ?>">
        <label for="comment">Commentaire</label>
        <textarea name="comment" id="comment" class="form-control" required><?= htmlspecialchars($comment->comment ?? '') ?></textarea>
        
        <label for="rating">Note</label>
        <select name="rating" id="rating" class="form-control" required>
            <option value="1" <?= $comment->rating == 1 ? 'selected' : '' ?>>1</option>
            <option value="2" <?= $comment->rating == 2 ? 'selected' : '' ?>>2</option>
            <option value="3" <?= $comment->rating == 3 ? 'selected' : '' ?>>3</option>
            <option value="4" <?= $comment->rating == 4 ? 'selected' : '' ?>>4</option>
            <option value="5" <?= $comment->rating == 5 ? 'selected' : '' ?>>5</option>
        </select>
        
        <input type="submit" value="Mettre à jour" class="mt-2 btn btn-primary">
    </form>
</body>

</html>

