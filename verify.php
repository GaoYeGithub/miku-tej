<?php
session_start();
require_once 'includes/yaml_db.php';

// session variables to count login attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// holds header info
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $favoriteSong = isset($_POST['favoriteSong']) ? trim($_POST['favoriteSong']) : '';
    
    // Validate
    if (empty($username) || empty($password)) {
        // error check
        header("Location: login.php?error=Username and password are required");
        exit();
    }
    
    // Validate
    $user = validateUser($username, $password);
    if ($user !== false) {
        // Login
        $_SESSION['login'] = $username;
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        // Store favorite song
        if (!empty($favoriteSong)) {
            $_SESSION['favorite_song'] = $favoriteSong;
        } elseif (!empty($user['favorite_song'])) {
            $_SESSION['favorite_song'] = $user['favorite_song'];
        }
        
        // Reset attempts
        $_SESSION['login_attempts'] = 0;
        
        // Success == Redirect
        header("Location: index.php?success=You have successfully logged in");
        exit();
    } else {
        $_SESSION['login_attempts']++;
        
        if ($_SESSION['login_attempts'] >= 3) {
            header("Location: login.php?error=Too many failed login attempts. Please try again later.");
            exit();
        } else {
            header("Location: login.php?error=Invalid username or password");
            exit();
        }
    }
} else {
    //  if not POST, redirect to login
    header("Location: login.php");
    exit();
}
?>

<?php
$page_title = "verify";
$current_page = 'verify';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="result-card text-center">
                <h3 class="result-error">Verification Error</h3>
                <p>There was an unexpected error during verification. Please try again.</p>
                <a href="login.php" class="btn btn-primary mt-3">Back to Login</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>