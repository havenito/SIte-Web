<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Se déonnecter</title>
</head>

<body>

</body>

</html>

<?php
session_start();
session_destroy();
header("Location: index.php");
?>