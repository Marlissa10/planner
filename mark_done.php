<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];
    $activityIds = $_POST['activity_done']; // Array of activity IDs marked as done

    // Update the activities as done in the database
    foreach ($activityIds as $activityId) {
        $query = "UPDATE activities SET is_done = 1 WHERE id = :activity_id AND user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':activity_id', $activityId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    }

    // Redirect back to the dashboard after marking activities as done
    header('Location: dashboard.php');
    exit();
} else {
    // If the form is not submitted, redirect back to the dashboard
    header('Location: dashboard.php');
    exit();
}
?>
