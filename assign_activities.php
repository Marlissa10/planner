<?php
session_start();
require 'connection.php';

// Check if user is logged in and is an IT Manager
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'IT Manager') {
    header('Location: login.php');
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $date = $_POST['date'];
    $assignedTo = $_POST['assigned_to']; // Assuming assigned_to is the user ID

    // Insert the activity into the database
    $query = "INSERT INTO activities (description, date, user_id) VALUES (:description, :date, :user_id)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':user_id', $assignedTo);
    $stmt->execute();
    // Redirect back to the dashboard after assigning the activity
    header('Location: dashboard.php');
    exit();
}

// Fetch list of users who can be assigned activities (IT Technicians, Executives, etc.)
$query = "SELECT id, username FROM users WHERE role != 'IT Manager'";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Activities</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="assign-activities-container">
        <h2>Assign Activities</h2>
        <form action="" method="post">
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" required></textarea><br>
            <label for="date">Date:</label><br>
            <input type="date" id="date" name="date" required><br>
            <label for="assigned_to">Assigned To:</label><br>
            <select id="assigned_to" name="assigned_to" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                <?php endforeach; ?>
            </select><br><br>
            <button type="submit">Assign</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
