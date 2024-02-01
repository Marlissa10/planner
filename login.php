<?php
include 'connection.php'; // Include the PDO connection file

session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a statement to prevent SQL injection
    $stmt = $db->prepare("SELECT id, username, role FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    
    // Fetch the result
    $user = $stmt->fetch();

    if ($user) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect user to dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Login failed
        $_SESSION['error'] = "Invalid username or password!";
        header('Location: index.php');
        exit();
    }
}
?>
