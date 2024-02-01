<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Get the current date
$currentDate = date('Y-m-d');

// Fetch activities for the logged-in user for the current date
$query = "SELECT * FROM activities WHERE user_id = :user_id AND date = :current_date";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $userId);
$stmt->bindParam(':current_date', $currentDate);
$stmt->execute();
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo $username; ?>!</h2>
        <h3>Role: <?php echo $role; ?></h3>
        <div class="activities">
            <h3>Activities for <?php echo $currentDate; ?>:</h3>
            <?php if (empty($activities)): ?>
                <p>No activities yet for today.</p>
            <?php else: ?>
                <form action="mark_done.php" method="post">
                    <ul>
                        <?php foreach ($activities as $activity): ?>
                            <li>
                                <input type="checkbox" name="activity_done[]" value="<?php echo $activity['id']; ?>" <?php echo $activity['is_done'] ? 'disabled' : ''; ?>>
                                <?php echo $activity['description']; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="button-group">
                        <button type="submit" class="btn">Mark Done</button>
                        <?php if ($role === 'IT Manager'): ?>
                            <a href="assign_activities.php" class="btn">Assign Activities</a>
                        <?php endif; ?>
                        <a href="logout.php" class="btn">Logout</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
