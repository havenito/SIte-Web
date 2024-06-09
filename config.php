<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Saveurs Gourmandes</title>
</head>

<body>


<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "Enzolise1976.";
$dbname = "cuisine2";
$port = 3306;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;port=$port;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    echo "Connection failed: " . $error->getMessage();
    exit;
}

class UsersManager
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }

    public function setDb(PDO $db): self
    {
        $this->db = $db;
        return $this;
    }

    public function readByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function createUser($firstName, $lastName, $email, $password)
    {
        $stmt = $this->db->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$firstName, $lastName, $email, password_hash($password, PASSWORD_BCRYPT)]);
    }
}

class RecipesManager
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }

    public function setDb(PDO $db): self
    {
        $this->db = $db;
        return $this;
    }

    public function createRecipe($userId, $title, $ingredients, $category, $preparation_time, $cooking_time, $steps, $image)
    {
        $stmt = $this->db->prepare("INSERT INTO recipes (user_id, title, ingredients, category, preparation_time, cooking_time, steps, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $title, $ingredients, $category, $preparation_time, $cooking_time, $steps, $image]);
    }

    public function getRecipes()
    {
        $stmt = $this->db->prepare("SELECT * FROM recipes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getRecipesByCategory($category)
    {
        if ($category) {
            $stmt = $this->db->prepare("SELECT * FROM recipes WHERE category = ?");
            $stmt->execute([$category]);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM recipes");
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getRecipeById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM recipes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}


class CommentsManager
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }

    public function setDb(PDO $db): self
    {
        $this->db = $db;
        return $this;
    }

    public function createComment($recipeId, $userId, $comment, $rating)
    {
        if ($this->hasUserCommented($recipeId, $userId)) {
            return false;
        }
        $stmt = $this->db->prepare("INSERT INTO comments (recipe_id, user_id, comment, rating) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$recipeId, $userId, $comment, $rating]);
    }

    public function hasUserCommented($recipeId, $userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM comments WHERE recipe_id = ? AND user_id = ?");
        $stmt->execute([$recipeId, $userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getCommentsByRecipe($recipeId)
    {
        $stmt = $this->db->prepare("
            SELECT comments.*, users.firstName, users.lastName
            FROM comments
            INNER JOIN users ON comments.user_id = users.id
            WHERE recipe_id = ?
        ");
        $stmt->execute([$recipeId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateComment($commentId, $comment, $rating)
    {
        $stmt = $this->db->prepare("UPDATE comments SET comment = ?, rating = ? WHERE id = ?");
        return $stmt->execute([$comment, $rating, $commentId]);
    }

    public function deleteComment($commentId)
    {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$commentId]);
    }

    public function getAverageRatingByRecipe($recipeId)
    {
        $stmt = $this->db->prepare("SELECT AVG(rating) as average_rating FROM comments WHERE recipe_id = ?");
        $stmt->execute([$recipeId]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result && $result->average_rating !== null ? round((float)$result->average_rating, 1) : null;
    }
}


$usersManager = new UsersManager($conn);
$recipesManager = new RecipesManager($conn);
$commentsManager = new CommentsManager($conn);
?>

</body>

</html>
