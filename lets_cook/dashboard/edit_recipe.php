<?php
session_start();
include "../config/database.php";

// kalau belum login
if (!isset($_SESSION['login'])) {
    header("Location: /lets_cook/index.php");
    exit;
}

// ambil id dari URL
$id = $_GET['id'];

// ambil data lama
$data = mysqli_query($conn, "SELECT * FROM recipes WHERE id='$id'");
$recipe = mysqli_fetch_assoc($data);

// kalau data ga ada
if (!$recipe) {
    echo "Data tidak ditemukan 😢";
    exit;
}

// proses update
if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];

    // cek upload gambar baru
    if ($_FILES['image']['name'] != "") {
        $image = $_FILES['image']['name'];
        $tmp   = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp, "../uploads/" . $image);
    } else {
        $image = $recipe['image']; // pakai gambar lama
    }

    $update = mysqli_query($conn, "
        UPDATE recipes SET
            title='$title',
            category='$category',
            ingredients='$ingredients',
            steps='$steps',
            image='$image'
        WHERE id='$id'
    ");

    if ($update) {
        header("Location: recipes.php");
        exit;
    } else {
        echo "Gagal update 😢";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Recipe - Let's Cook</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
        }

        header {
            background: #3e2723;
            color: white;
            padding: 15px 30px;
        }

        .container {
            padding: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            max-width: 600px;
            box-shadow: 0 4px 10px rgba(0,0,0,.1);
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background: #3e2723;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background: #5d4037;
        }

        img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        a {
            text-decoration: none;
            color: #3e2723;
        }
    </style>
</head>
<body>

<header>
    <h3>Edit Recipe ✏️</h3>
</header>

<div class="container">
    <div class="card">

        <form method="post" enctype="multipart/form-data">
            <label>Judul Resep</label>
            <input type="text" name="title" value="<?= $recipe['title']; ?>" required>

            <label>Kategori</label>
            <select name="category" required>
                <option value="Makanan" <?= $recipe['category']=='Makanan'?'selected':''; ?>>Makanan</option>
                <option value="Minuman" <?= $recipe['category']=='Minuman'?'selected':''; ?>>Minuman</option>
                <option value="Dessert" <?= $recipe['category']=='Dessert'?'selected':''; ?>>Dessert</option>
            </select>

            <label>Bahan-bahan</label>
            <textarea name="ingredients" rows="4" required><?= $recipe['ingredients']; ?></textarea>

            <label>Langkah-langkah</label>
            <textarea name="steps" rows="4" required><?= $recipe['steps']; ?></textarea>

            <label>Gambar Sekarang</label><br>
            <?php if ($recipe['image']) : ?>
                <img src="../uploads/<?= $recipe['image']; ?>">
            <?php endif; ?>

            <label>Ganti Gambar (opsional)</label>
            <input type="file" name="image">

            <button type="submit" name="update">Update</button>
        </form>

        <br>
        <a href="recipes.php">⬅ Kembali ke Recipes</a>

    </div>
</div>

</body>
</html>
