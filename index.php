<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 Ultimate Quiz</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" type="image/x-icon" href="media/logo.ico">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="quiz-container">
        <header>
            <h1>F1 <span>ULTIMATE</span> QUIZ</h1>
        </header>

        <div id="start-screen" class="screen active">
            <h2>Test Your Formula 1 Knowledge</h2>
            <p>Do you have what it takes to finish P1? Start the lights to find out.</p>
            
            <select id="difficulty-select" class="btn secondary-btn" style="background-color: var(--card-bg); margin-bottom: 20px;">
                <option value="easy">Easy (Safety Car Pace) - 10 Pts/Q</option>
                <option value="medium" selected>Medium (Race Pace) - 20 Pts/Q</option>
                <option value="hard">Hard (Qualifying Pace) - 30 Pts/Q</option>
            </select>

            <button id="start-btn" class="btn primary-btn">START RACE</button>
            <button class="btn secondary-btn" onclick="window.location.href='dashboard.php'">BACK TO PIT WALL</button>
        </div>

        <div id="quiz-screen" class="screen">
            <div class="progress-container">
                <span id="question-tracker">Question 1/10</span>
                <div class="progress-bar">
                    <div id="progress-fill"></div>
                </div>
            </div>
            
            <h2 id="question-text">Question goes here?</h2>
            
            <div id="options-container" class="options-grid">
            </div>

            <button id="next-btn" class="btn secondary-btn hide">NEXT LAP <span>&#10140;</span></button>
        </div>

        <div id="result-screen" class="screen">
            <h2>CHEQUERED FLAG!</h2>
            <p>Your Final Classification:</p>
            <div class="score-display">
                <span id="score-text">0</span> / <span id="total-text">10</span>
            </div>
            <h3 style="color: var(--correct); margin-bottom: 15px; font-style: italic; letter-spacing: 1px;">
                POINTS EARNED: <span id="points-text">0</span>
            </h3>
            <p id="feedback-text">Good effort!</p>
            <button id="restart-btn" class="btn primary-btn">RETURN TO DASHBOARD</button>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>