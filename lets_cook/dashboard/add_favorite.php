<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['login'])) {
    header("Location: /lets_cook/index.php");
    exit;
}

$user = $_SESSION['username'];
$recipe_id = $_GET['id'];

// ambil user_id
$getUser = mysqli_query($conn, "SELECT id FROM users WHERE username='$user'");
$userData = mysqli_fetch_assoc($getUser);
$user_id = $userData['id'];

// cek biar ga dobel
$cek = mysqli_query(
    $conn,
    "SELECT * FROM favorites 
     WHERE user_id='$user_id' AND recipe_id='$recipe_id'"
);

if (mysqli_num_rows($cek) == 0) {
    mysqli_query(
        $conn,
        "INSERT INTO favorites (user_id, recipe_id)
         VALUES ('$user_id', '$recipe_id')"
    );
}

header("Location: index.php");
