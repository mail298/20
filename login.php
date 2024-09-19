<?php
session_start();
// Jika session user tidak ada, arahkan ke login page
if (isset($_SESSION["username"])) {
    header("Location: dashboard.php");
    exit;
}
include 'includes/functions.php';

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (loginUser($username, $password)) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $errorMessage = "Username dan password salah!.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- <header>
        <nav>
            <ul>
                <li>
                    <a href="index.php">Home</a>
                </li>
            </ul>
        </nav>
    </header> -->
    <form action="login.php" method="post">
        <h2>Login</h2>
        <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</body>
</html>
