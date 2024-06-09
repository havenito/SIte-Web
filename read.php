<?php
include 'config.php';

try {
    $stmt = $conn->prepare("SELECT * FROM recipes");
    $stmt->execute();
    $recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    echo "Erreur : " . $error->getMessage();
    $recettes = [];
}

echo json_encode($recettes);
?>

<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Liste des Recettes</title>
</head>

<body>
    <h1>Liste des Recettes</h1>
    <pre id="recettes"></pre>
<script src="read.js"></script>
</body>

</html>