<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (registerUser($username, $email, $password)) {
        redirect('index.php');
    } else {
        $error = 'Registration failed. Email may already be in use.';
    }
}

include 'header.php';
?>
<style>
    .login-container {
    max-width: 400px;
    margin: 4rem auto;
    padding: 2rem 1.5rem;
}

.login-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #6b21a8;
    margin-bottom: 1.5rem;
}

.error-box {
    background-color: #fee2e2;
    border: 1px solid #fca5a5;
    color: #b91c1c;
    padding: 1rem;
    border-radius: 0.375rem;
    margin-bottom: 1rem;
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    margin-bottom: 0.5rem;
    color: #374151;
    font-weight: 500;
}

.form-input {
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 1rem;
    outline: none;
}

.form-input:focus {
    border-color: #9333ea;
    box-shadow: 0 0 0 2px #c084fc;
}

.login-button {
    width: 100%;
    background-color: #6b21a8;
    color: white;
    padding: 0.5rem 1rem;
    font-weight: 600;
    border-radius: 0.375rem;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.login-button:hover {
    background-color: #5b21b6;
}

.register-link {
    margin-top: 1rem;
    font-size: 0.95rem;
    color: #4b5563;
}

.register-link a {
    color: #6b21a8;
    text-decoration: none;
    font-weight: 500;
}

.register-link a:hover {
    text-decoration: underline;
}

</style>
<div class="login-container">
    <h1 class="login-title">Register</h1>

    <?php if ($error): ?>
        <div class="error-box">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="login-form">
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" required class="form-input">
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" required class="form-input">
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" required class="form-input">
        </div>

        <div class="form-group">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required class="form-input">
        </div>

        <button type="submit" class="login-button">Register</button>
    </form>

    <p class="register-link">
        Already have an account? <a href="login.php">Login here</a>
    </p>
</div>

<?php include 'footer.php'; ?>
