

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
include 'includes/functions.php';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($password === $confirmPassword) {
        if (registerUser($username, $email, $password)) {
            $successMessage = "Pendaftaran berhasil!";
        } else {
            $errorMessage = "Kesalahan: Tidak dapat mendaftar, username atau email sudah terdaftar.";
        }
    } else {
        $errorMessage = "Kata sandi tidak cocok.";
    }
}
?>

    <form action="register.php" method="post">
            <h2>Register</h2>
            <?php if (!empty($errorMessage)): ?>
                <p class="error-message"><?php echo htmlspecialchars($errorMessage); ?></p>
            <?php elseif (!empty($successMessage)): ?>
                <p class="success-message"><?php echo htmlspecialchars($successMessage); ?></p>
            <?php endif; ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</body>
</html>
