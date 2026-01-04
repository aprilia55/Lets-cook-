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

// ambil data (buat hapus gambar)
$data = mysqli_query($conn, "SELECT image FROM recipes WHERE id='$id'");
$recipe = mysqli_fetch_assoc($data);

// hapus gambar kalau ada
if ($recipe && $recipe['image'] != "") {
    $file = "../uploads/" . $recipe['image'];
    if (file_exists($file)) {
        unlink($file);
    }
}

// hapus data dari database
$delete = mysqli_query($conn, "DELETE FROM recipes WHERE id='$id'");

if ($delete) {
    header("Location: recipes.php");
    exit;
} else {
    echo "Gagal hapus 😢";
}
