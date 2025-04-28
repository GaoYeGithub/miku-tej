<?php
session_start();

// if user logged in
if (!isset($_SESSION['login'])) {
    header("Location: login.php?error=You must be logged in to add favorites");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['image_title'])) {
    $image_title = $_POST['image_title'];
    
    if (!isset($_SESSION['favorite_images'])) {
        $_SESSION['favorite_images'] = array();
    }
    
    if (!in_array($image_title, $_SESSION['favorite_images'])) {
        $_SESSION['favorite_images'][] = $image_title;
        header("Location: gallery.php?success=Image added to favorites");
    } else {
        header("Location: gallery.php?error=Image already in favorites");
    }
    exit();
} else {
    header("Location: gallery.php");
    exit();
}
?>