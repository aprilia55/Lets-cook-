<?php
session_start();
include "../config/database.php";

// kalau belum login, balik ke login
if (!isset($_SESSION['login'])) {
    header("Location: /lets_cook/index.php");
    exit;
}

// proses simpan data
if (isset($_POST['simpan'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];

    // upload gambar
    $image = $_FILES['image']['name'];
    $tmp   = $_FILES['image']['tmp_name'];

    if ($image != "") {
        move_uploaded_file($tmp, "../uploads/" . $image);
    }

    $query = mysqli_query($conn, "
        INSERT INTO recipes (title, category, ingredients, steps, image)
        VALUES ('$title', '$category', '$ingredients', '$steps', '$image')
    ");

    if ($query) {
        header("Location: recipes.php");
        exit;
    } else {
        echo "Gagal simpan 😢";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Recipe - Let's Cook</title>

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

        a {
            text-decoration: none;
            color: #3e2723;
        }
    </style>
</head>
<body>

<header>
    <h3>Tambah Recipe 🍳</h3>
</header>

<div class="container">
    <div class="card">

        <form method="post" enctype="multipart/form-data">
            <label>Judul Resep</label>
            <input type="text" name="title" required>

            <label>Kategori</label>
            <select name="category" required>
                <option value="">-- pilih --</option>
                <option value="Makanan">Makanan</option>
                <option value="Minuman">Minuman</option>
                <option value="Dessert">Dessert</option>
            </select>

            <label>Bahan-bahan</label>
            <textarea name="ingredients" rows="4" required></textarea>

            <label>Langkah-langkah</label>
            <textarea name="steps" rows="4" required></textarea>

            <label>Gambar</label>
            <input type="file" name="image">

            <button type="submit" name="simpan">Simpan</button>
        </form>

        <br>
        <a href="recipes.php">⬅ Kembali ke Recipes</a>

    </div>
</div>

</body>
</html>
