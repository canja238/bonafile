<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit();
}

// Login function
function loginUser($email, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    }

    return false;
}

// Register function
function registerUser($username, $email, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return false;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
    $success = $stmt->execute([$username, $email, $hashedPassword]);

    if ($success) {
        return loginUser($email, $password);
    }

    return false;
}
