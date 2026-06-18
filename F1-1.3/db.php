<?php
$host = 'localhost';
$db = 'f1_quiz';
$user = 'root'; // Default XAMPP username
$pass = '';     // Default XAMPP password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to log user actions for admin monitoring
function logUserAction($conn, $user_id, $action) {
    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $action);
    $stmt->execute();
}
?>