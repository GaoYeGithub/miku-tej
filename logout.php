<?php
session_start();

$_SESSION = array();
session_destroy();

// Redirect
header("Location: index.php?success=You have been logged out successfully");
exit();
?>