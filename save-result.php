<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

// Check if the driver is authenticated on the pit wall
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Driver session missing. Unauthenticated access.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Catch the incoming JSON payload from your frontend JavaScript
$inputData = json_decode(file_get_contents('php://input'), true);

if (isset($inputData['score'])) {
    $score = (int)$inputData['score'];
    $accuracy = isset($inputData['accuracy']) ? (int)$inputData['accuracy'] : 0;

    // Start a transaction so both database updates must succeed together
    $conn->begin_transaction();

    try {
        // 1. Insert into your new quiz result history table
        $stmt1 = $conn->prepare("INSERT INTO quizresult (userID, score, accuracy) VALUES (?, ?, ?)");
        $stmt1->bind_param("iii", $user_id, $score, $accuracy);
        $stmt1->execute();

        // 2. Add the session points to the driver's main cumulative leaderboard total
        $stmt2 = $conn->prepare("UPDATE users SET super_license_points = super_license_points + ? WHERE id = ?");
        $stmt2->bind_param("ii", $score, $user_id);
        $stmt2->execute();

        // If no errors occurred, safely push changes to the grid
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Telemetry and score locked into data logs successfully.']);

    } catch (Exception $e) {
        // Rollback the database state if any query stalls out
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid telemetry payload data received.']);
}
?>