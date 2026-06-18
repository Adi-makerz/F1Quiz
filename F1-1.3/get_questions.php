<?php
require 'db.php';
header('Content-Type: application/json');

// Capture difficulty from frontend, default to medium
$difficulty = isset($_GET['difficulty']) ? $_GET['difficulty'] : 'medium';

// Fetch random questions matching the difficulty
$stmt = $conn->prepare("SELECT * FROM questions WHERE difficulty = ? ORDER BY RAND() LIMIT 10");
$stmt->bind_param("s", $difficulty);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = [
        'id' => $row['id'],
        'question' => $row['question'],
        'options' => [
            $row['option_a'],
            $row['option_b'],
            $row['option_c'],
            $row['option_d']
        ],
        'answer' => $row['correct_answer']
    ];
}

echo json_encode($questions);
?>  