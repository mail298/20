<?php
include 'db.php';

function registerUser($username, $email, $password) {
    global $conn;
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO mydatabase (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $passwordHash);
    return $stmt->execute();
}

function loginUser($username, $password) {
    global $conn;
    $sql = "SELECT password FROM mydatabase WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($passwordHash);
    $stmt->fetch();
    return password_verify($password, $passwordHash);
}
?>
