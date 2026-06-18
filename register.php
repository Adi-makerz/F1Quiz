<?php
session_start();
require 'db.php';

$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    // Securely hash the password
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $teamID = $_POST['team']; 

    // Changed 'team' to 'teamID' inside the column definition list
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, teamID, super_license_points) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("ssss", $username, $password, $email, $teamID);

    if ($stmt->execute()) {
        // 1. Save the success notification to the session so the login page can display it
        $_SESSION['reg_success'] = "Super License granted! Welcome to the grid.";
        
        // 2. Redirect the driver immediately to the login page
        header("Location: login.php");
        exit(); 
    } else {
        $error_msg = "Username or email already exists on the grid.";
    }
}

// Dynamically fetch your real F1 Team options from the Team table
$team_query = $conn->query("SELECT teamID, teamName FROM Team");
$teams = [];
if($team_query){
    while($row = $team_query->fetch_assoc()){
        $teams[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 Grid Quiz - Register</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" type="image/x-icon" href="media/logo.ico">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>F1 <span>PIT WALL</span> REGISTRATION</h1>
            <p>Apply for your Super License.</p>
        </div>
        
        <?php if($error_msg): ?> <p style="color:var(--f1-red); text-align:center; margin-bottom:15px;"><b><?php echo $error_msg; ?></b></p> <?php endif; ?>

        <form class="login-form" method="POST" action="register.php">
            <div class="input-group">
                <label for="username">Driver Name (Username)</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="email">Comms Channel (Email)</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="team">Constructor Allegiance</label>
                <select id="team" name="team" style="background-color: var(--f1-bg-main); border: 1px solid #333; color: var(--text-light); padding: 14px 15px; border-radius: 4px; font-size: 1rem; width:100%; margin-top:5px;">
                    <option value="">-- Choose Your Constructor --</option>
                    <?php foreach($teams as $team_option): ?>
                        <option value="<?php echo $team_option['teamID']; ?>">
                            <?php echo htmlspecialchars($team_option['teamName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-group">
                <label for="password">Telemetry Key (Password)</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="login-btn">BOX CONFIRM - SIGN UP</button>
        </form>
        
        <div class="signup-link">
            <p>Already on the grid? <a href="login.php">Return to Pit Wall</a></p>
        </div>
    </div>
</body>
</html>