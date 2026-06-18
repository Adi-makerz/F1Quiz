<?php
session_start();
require 'db.php';

// Force PHP to report any hidden SQL errors out loud
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    
    // Grab variables from the Javascript FormData
    $score = isset($_POST['score']) ? (int)$_POST['score'] : 0;
    $correct = isset($_POST['correct']) ? (int)$_POST['correct'] : 0;
    $difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : 'unknown';
    $user_id = $_SESSION['user_id'];

    // Calculate accuracy percentage dynamically
    $accuracy = ($correct / 10) * 100;

    // 1. UPDATE USER POINTS TOTAL
    if ($score > 0) {
        $stmt1 = $conn->prepare("UPDATE users SET super_license_points = super_license_points + ? WHERE id = ?");
        $stmt1->bind_param("ii", $score, $user_id);
        $stmt1->execute();
        $stmt1->close();
    }
    
    // 2. INSERT INTO QUIZ RESULTS HISTORY
    // We explicitly name every column to match your image structure exactly:
    // (userID, accuracy, score, Qdifficulty)
    $stmt2 = $conn->prepare("INSERT INTO quizresult (userID, accuracy, score, Qdifficulty) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiis", $user_id, $accuracy, $score, $difficulty);
    $stmt2->execute();
    $stmt2->close();

    // 3. RUN LOG USER ACTION TEXT LOGGER
    if (function_exists('logUserAction')) {
        logUserAction($conn, $user_id, "Completed $difficulty quiz: $correct/10 correct. Earned $score pts.");
    }

    echo "Success";
} else {
    echo "Error: Unauthenticated access or invalid method request.";
}
?>