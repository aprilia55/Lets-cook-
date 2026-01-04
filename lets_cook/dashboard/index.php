<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['login'])) {
    header("Location: /lets_cook/index.php");
    exit;
}

$keyword = isset($_GET['search']) ? $_GET['search'] : "";

$query = mysqli_query(
    $conn,
    "SELECT * FROM recipes 
     WHERE title LIKE '%$keyword%' 
     ORDER BY created_at DESC 
     LIMIT 8"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard - Let's Cook</title>

<style>
* {
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    margin: 0;
    display: flex;
    background: #fafafa;
    min-height: 100vh;
}

/* SIDEBAR */
.sidebar {
    width: 240px;
    height: 100vh;
    background: #ffffff;
    border-right: 1px solid #ddd;
    padding: 20px;
    position: fixed;
    top: 0;
    left: 0;
}

.sidebar h2 {
    color: #ff6f00;
    text-align: center;
    margin-bottom: 30px;
}

.sidebar a {
    display: block;
    text-decoration: none;
    color: #333;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 10px;
    font-weight: bold;
}

.sidebar a:hover {
    background: #ffe0b2;
}

/* CONTENT */
.content {
    margin-left: 240px;
    flex: 1;
    padding: 40px;
}

/* HEADER */
.header {
    text-align: center;
    margin-bottom: 40px;
}

.header h1 {
    color: #ff6f00;
    margin-bottom: 5px;
    font-size: 32px;
}

.header p {
    color: #555;
    margin-bottom: 20px;
}

/* SEARCH */
.search-box {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.search-box input {
    width: 380px;
    padding: 14px;
    border-radius: 30px;
    border: 1px solid #ccc;
    font-size: 16px;
}

.search-box button {
    padding: 14px 30px;
    border-radius: 30px;
    border: none;
    background: #ff6f00;
    color: white;
    cursor: pointer;
    font-size: 16px;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 25px;
}

.card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 6px 15px rgba(0,0,0,.15);
    transition: transform .2s;
}

.card:hover {
    transform: translateY(-5px);
}

.card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.card .info {
    padding: 18px;
}

.card h4 {
    margin: 0 0 5px;
    font-size: 18px;
}

.card small {
    color: gray;
}

.favorite {
    display: inline-block;
    margin-top: 10px;
    color: #ff6f00;
    font-weight: bold;
    text-decoration: none;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>🍳 Let's Cook</h2>
    <a href="index.php">🏠 Dashboard</a>
    <a href="recipes.php">📖 Recipes</a>
    <a href="add_recipe.php">➕ Tulis Resep</a>
    <a href="favorites.php">⭐ Favorit</a>
    <a href="/lets_cook/auth/logout.php">🚪 Logout</a>
</div>

<!-- CONTENT -->
<div class="content">

    <div class="header">
        <h1>Let's Cook 🍳</h1>
        <p>Mau masak apa hari ini?</p>

        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Cari resep..." value="<?= $keyword ?>">
            <button type="submit">Cari</button>
        </form>
    </div>

    <div class="grid">
        <?php if (mysqli_num_rows($query) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <div class="card">

                <!-- CARD BISA DIKLIK -->
                <a href="detail_recipe.php?id=<?= $row['id']; ?>" style="text-decoration:none;color:inherit;">
                    <img src="../assets/default.jpg">
                    <div class="info">
                        <h4><?= $row['title']; ?></h4>
                        <small><?= $row['category']; ?></small>
                    </div>
                </a>

                <!-- FAVORITE -->
                <div class="info">
                    <a href="add_favorite.php?id=<?= $row['id']; ?>" class="favorite">
                        ❤️ Favorite
                    </a>
                </div>

            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Resep tidak ditemukan 😢</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
