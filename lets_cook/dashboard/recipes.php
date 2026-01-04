<?php
session_start();
include "../config/database.php";

// kalau belum login
if (!isset($_SESSION['login'])) {
    header("Location: /lets_cook/index.php");
    exit;
}

// ambil keyword search
$keyword = isset($_GET['search']) ? $_GET['search'] : "";

// query recipes
$query = mysqli_query(
    $conn,
    "SELECT * FROM recipes 
     WHERE title LIKE '%$keyword%' 
     OR category LIKE '%$keyword%' 
     ORDER BY created_at DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Recipes - Let's Cook</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            display: flex;
            background: #f5f5f5;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            background: #3e2723;
            color: white;
            min-height: 100vh;
            padding: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 10px;
            background: #5d4037;
        }

        .menu a:hover {
            background: #795548;
        }

        .logout {
            margin-top: 30px;
            display: block;
            text-align: center;
            background: #b71c1c;
            padding: 10px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
        }

        /* CONTENT */
        .content {
            flex: 1;
            padding: 30px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-box input {
            padding: 10px;
            width: 250px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .search-box button {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            background: #3e2723;
            color: white;
            cursor: pointer;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,.1);
        }

        th, td {
            padding: 12px;
        }

        th {
            background: #3e2723;
            color: white;
            text-align: left;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 14px;
        }

        .edit {
            background: #795548;
        }

        .delete {
            background: #b71c1c;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>🍳 Let's Cook</h2>

    <div class="menu">
        <a href="index.php">🏠 Dashboard</a>
        <a href="recipes.php">📖 Data Recipes</a>
        <a href="add_recipe.php">➕ Tambah Recipe</a>
    </div>

    <a href="/lets_cook/auth/logout.php" class="logout">Logout</a>
</div>

<!-- CONTENT -->
<div class="content">

    <div class="top">
        <h2>Data Recipes 🍽️</h2>

        <!-- SEARCH -->
        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Cari resep..." value="<?= $keyword; ?>">
            <button type="submit">Cari</button>
        </form>
    </div>

    <table>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>

        <?php if (mysqli_num_rows($query) > 0): ?>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($query)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['title']; ?></td>
                <td><?= $row['category']; ?></td>
                <td>
                    <a href="edit_recipe.php?id=<?= $row['id']; ?>" class="btn edit">Edit</a>
                    <a href="delete_recipe.php?id=<?= $row['id']; ?>" 
                       class="btn delete"
                       onclick="return confirm('Yakin mau hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" align="center">Data tidak ditemukan 😢</td>
            </tr>
        <?php endif; ?>
    </table>

</div>

</body>
</html>
