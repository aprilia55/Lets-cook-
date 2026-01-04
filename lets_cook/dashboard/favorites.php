<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['login'])) {
    header("Location: /lets_cook/index.php");
    exit;
}

$user = $_SESSION['username'];

// ambil user_id
$getUser = mysqli_query($conn, "SELECT id FROM users WHERE username='$user'");
$userData = mysqli_fetch_assoc($getUser);
$user_id = $userData['id'];

$query = mysqli_query(
    $conn,
    "SELECT recipes.* FROM favorites
     JOIN recipes ON favorites.recipe_id = recipes.id
     WHERE favorites.user_id = '$user_id'
     ORDER BY favorites.created_at DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Favorit - Let's Cook</title>
</head>
<body>

<h2>⭐ Resep Favorit Kamu</h2>

<?php if (mysqli_num_rows($query) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($query)): ?>
        <p>🍽️ <?= $row['title']; ?> (<?= $row['category']; ?>)</p>
    <?php endwhile; ?>
<?php else: ?>
    <p>Belum ada resep favorit 😢</p>
<?php endif; ?>

</body>
</html>
