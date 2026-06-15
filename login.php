<?php
session_start();
require 'db.php';

$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // FIXED: Using password_verify() to read the secure hash.
        // The fallback '|| $password === $row['password']' keeps plain-text accounts working until they reset their password.
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            
            // Log action if function exists
            if (function_exists('logUserAction')) {
                logUserAction($conn, $row['id'], "Logged into Pit Wall");
            }
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error_msg = "Invalid Telemetry Key.";
        }
    } else {
        $error_msg = "Driver not found on the grid.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 Grid Quiz - Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>F1 <span>PIT WALL</span> LOGIN</h1>
            <p>Access the telemetry and start the quiz.</p>
        </div>
        
        <!-- FIXED: Catch and display the successful registration notice from register.php -->
        <?php if (isset($_SESSION['reg_success'])): ?>
            <p style="color:#00d2be; text-align:center; margin-bottom:15px; font-size:1.05rem;">
                <b><?php echo $_SESSION['reg_success']; ?></b>
            </p>
            <?php unset($_SESSION['reg_success']); // Clears it immediately so it doesn't reappear on manual page refreshes! ?>
        <?php endif; ?>

        <?php if($error_msg): ?>
            <p style="color:var(--f1-red); text-align:center; margin-bottom:15px;"><b><?php echo $error_msg; ?></b></p>
        <?php endif; ?>

        <form class="login-form" method="POST" action="login.php">
            <div class="input-group">
                <label for="username">Driver Name (Username)</label>
                <input type="text" id="username" name="username" placeholder="e.g., MaxVerstappen1" required>
            </div>
            
            <div class="input-group">
                <label for="password">Telemetry Key (Password)</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="login-btn">LIGHTS OUT - LOGIN</button>
        </form>
        
        <div class="signup-link">
            <p>Rookie on the grid? <a href="register.php">Sign up for your Super License</a></p>
        </div>
        
        <div class="admin-login-link">
            <p>Team Principal? <a href="admin-login.php">Admin Login</a></p>
        </div>
    </div>
</body>
</html>