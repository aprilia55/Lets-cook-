<?php
session_start();
include "../config/database.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE username='$username' AND password='$password'"
);

if (mysqli_num_rows($query) > 0) {
    $_SESSION['login'] = true;
    $_SESSION['username'] = $username;

    header("Location: /lets_cook/dashboard/index.php");
    exit;
} else {
    echo "Login gagal 😢";
}
