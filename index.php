<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="category.css">
    <link rel="stylesheet" href="index.css">
    <title>Saveurs Gourmandes</title>
</head>

<body>
    <?php
    require("config.php");
    require("header.php");

    $selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

    $dbRecipes = $recipesManager->getRecipesByCategory($selectedCategory);

    $dbRecipesArray = [];
    foreach ($dbRecipes as $recipe) {
        $dbRecipesArray[] = (array) $recipe;
    }

    $jsonFilePath = 'recipesv1.json';
    $jsonRecipes = [];
    if (file_exists($jsonFilePath)) {
        $jsonRecipes = json_decode(file_get_contents($jsonFilePath), true);
    }

    $allRecipes = array_merge($dbRecipesArray, $jsonRecipes);

    if ($selectedCategory) {
        $allRecipes = array_filter($allRecipes, function($recipe) use ($selectedCategory) {
            return $recipe['category'] == $selectedCategory;
        });
    }
    ?>
    
    <h1>Saveurs Gourmandes</h1>
    <br>
    <input type="text" id="searchBar" placeholder="Rechercher une recette..." onkeyup="filterRecipes()">

    <form method="get" action="index.php" class="category-form">
        <select name="category" onchange="this.form.submit()" class="category-select">
            <option value="">Toutes les catégories</option>
            <option value="entrée" <?= $selectedCategory == 'entrée' ? 'selected' : '' ?>>Entrée</option>
            <option value="plat" <?= $selectedCategory == 'plat' ? 'selected' : '' ?>>Plat</option>
            <option value="dessert" <?= $selectedCategory == 'dessert' ? 'selected' : '' ?>>Dessert</option>
        </select>
    </form>

    <div class="recipes" id="recipesContainer">
        <?php foreach ($allRecipes as $index => $recipe) :
            $averageRating = isset($recipe['id']) ? $commentsManager->getAverageRatingByRecipe($recipe['id']) : null;
        ?>
            <div class="recipe" id="recipe-<?= $index ?>">
                <h2><?= htmlspecialchars($recipe['title']) ?></h2>
                <p>Catégorie : <?= htmlspecialchars($recipe['category']) ?></p>
                <p>Temps de préparation : <?= htmlspecialchars($recipe['preparation_time']) ?></p>
                <p>Temps de cuisson : <?= htmlspecialchars($recipe['cooking_time']) ?></p>
                <?php if ($averageRating !== null): ?>
                    <p class="average-rating">Moyenne des notes : <?= $averageRating ?>/5</p>
                <?php else: ?>
                    <p class="average-rating">Pas encore noté</p>
                <?php endif; ?>
                <div class="recipe-info">
                    <?php if (isset($recipe['id'])): ?>
                        <a href="recipe.php?id=<?= $recipe['id'] ?>">Voir la recette</a>
                        <?php if (isset($_SESSION["user_id"]) && isset($recipe['user_id']) && $_SESSION["user_id"] == $recipe['user_id']): ?>
                            <a href="update.php?id=<?= $recipe['id'] ?>">Modifier</a>
                            <a href="delete.php?id=<?= $recipe['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette recette ?');">Supprimer</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php if (!empty($recipe['image'])): ?>
                    <img src="<?= htmlspecialchars($recipe['image']) ?>" alt="Image de la recette" class="recipe-image">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php require("footer.php"); ?>
    <script src="script.js"></script>
</body>
</html>
