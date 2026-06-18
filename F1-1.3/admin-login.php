<?php
session_start();
require 'db.php';

$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($password === $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            header("Location: admin-dashboard.php");
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
    <title>F1 Grid Quiz - Admin Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>F1 <span>PIT WALL</span> ADMIN LOGIN</h1>
            <p>Access the admin panel and manage the quiz.</p>
        </div>
        
        <?php if($error_msg): ?>
            <p style="color:var(--f1-red); text-align:center; margin-bottom:15px;"><b><?php echo $error_msg; ?></b></p>
        <?php endif; ?>

        <form class="login-form" method="POST" action="admin-login.php">
            <div class="input-group">
                <label for="username">Admin Username</label>
                <input type="text" id="username" name="username" placeholder="e.g., AdminUser" required>
            </div>
            
            <div class="input-group">
                <label for="password">Telemetry Key (Password)</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="login-btn">LIGHTS OUT - ADMIN LOGIN</button>
        </form>
        
        <div class="signup-link">
            <p>Driver? <a href="login.php">Regular Login</a></p>
        </div>
    </div>  
</body>
</html>