<?php
$page_title = "Register";
$current_page = 'register';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'includes/yaml_db.php';

// submission vars
$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $favoriteSong = isset($_POST['favoriteSong']) ? trim($_POST['favoriteSong']) : '';
    
    // Validate
    if (empty($username) || empty($password)) {
        $error_message = "Username and password are required";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match";
    } elseif (strlen($username) < 3) {
        $error_message = "Username must be at least 3 characters";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters";
    } else {
        // add user to database
        if (addUser($username, $password, $favoriteSong)) {
            $success_message = "Registration successful! You can now login.";
        } else {
            $error_message = "Username already exists. Please choose another one.";
        }
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Register</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="favoriteSong" class="form-label">Favorite Miku Song (Optional)</label>
                            <input type="text" class="form-control" id="favoriteSong" name="favoriteSong">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Already have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>