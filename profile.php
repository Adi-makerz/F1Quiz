<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$status_msg = "";
$msg_type = ""; // To differentiate between success (cyan/green) and error (red) colors

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $teamID = $_POST['team'];
    $password_updated = false;
    
    // Check if the user intends to change their password
    if (!empty($_POST['new-password'])) {
        if ($_POST['new-password'] === $_POST['confirm-password']) {
            // Securely hashes the password. (Shows as a long string in phpMyAdmin, but verifies perfectly on login)
            $new_password = password_hash($_POST['new-password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET email=?, teamID=?, password=? WHERE id=?");
            $stmt->bind_param("sssi", $email, $teamID, $new_password, $user_id);
            $password_updated = true;
        } else {
            // Passwords mismatch error state trigger
            $status_msg = "Telemetry Key Update Failed: Passwords do not match.";
            $msg_type = "error";
        }
    } else {
        // No password change requested, update email and team allegiance only
        $stmt = $conn->prepare("UPDATE users SET email=?, teamID=? WHERE id=?");
        $stmt->bind_param("ssi", $email, $teamID, $user_id);
    }
    
    // Execute the database write if no matching password mismatch occurred prior
    if ($msg_type !== "error") {
        if ($stmt->execute()) {
            $status_msg = $password_updated 
                ? "Telemetry and Security Protocols updated successfully." 
                : "Driver credentials updated successfully.";
            $msg_type = "success";
        } else {
            $status_msg = "Database Error: Failed to write telemetry updates to the grid.";
            $msg_type = "error";
        }
    }
}

// Fetch current details to populate form
$stmt = $conn->prepare("SELECT username, email, teamID FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();

$team_query = $conn->query("SELECT teamID, teamName FROM Team");
$teams = [];
if($team_query){
    while($row = $team_query->fetch_assoc()){
        $teams[] = $row;
    }
}

// =========================================================================
// NEW: LIVE TELEMETRY LOGS QUERIES FOR PROFILE METRICS
// =========================================================================
// 1. Fetch career aggregates (Total Races and Average Accuracy)
$stats_query = $conn->prepare("SELECT COUNT(*) as total_races, AVG(accuracy) as avg_accuracy FROM quizresult WHERE userID = ?");
$stats_query->bind_param("i", $user_id);
$stats_query->execute();
$stats = $stats_query->get_result()->fetch_assoc();

$total_races = $stats['total_races'] ?? 0;
$avg_accuracy = isset($stats['avg_accuracy']) ? round($stats['avg_accuracy'], 1) : 0;

// 2. Fetch the 5 most recent completed session rows (tracking dateCompleted)
// Configured to dynamically pull from your exact database column setup
$history_stmt = $conn->prepare("SELECT accuracy, score, Qdifficulty, dateCompleted FROM quizresult WHERE userID = ? ORDER BY dateCompleted DESC LIMIT 5");
$history_stmt->bind_param("i", $user_id);
$history_stmt->execute();
$history_results = $history_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 Grid Quiz - Driver Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <style>
        /* Custom layout modifications matching the F1 Pit Wall Design System */
        .telemetry-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
            width: 100%;
        }
        .stat-card {
            background: #151922;
            border-radius: 6px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            box-sizing: border-box;
        }
        .stat-card.races { border-left: 4px solid #e10600; }
        .stat-card.accuracy { border-left: 4px solid #00ffd0; }
        .stat-card p {
            margin: 0;
            font-size: 0.8rem;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }
        .stat-card h2 {
            margin: 8px 0 0 0;
            font-size: 2rem;
            color: #fff;
        }
        .stat-card h2 span { font-size: 1rem; color: #555; font-weight: normal; }
        .history-card {
            background: #151922;
            border: 1px solid #232936;
            border-radius: 6px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            box-sizing: border-box;
            width: 100%;
        }
        .history-card h3 {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #232936;
            padding-bottom: 12px;
            color: #fff;
        }
        .telemetry-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .telemetry-table th {
            color: #666;
            font-size: 0.8rem;
            text-transform: uppercase;
            padding: 10px 5px;
            border-bottom: 1px solid #232936;
        }
        .telemetry-table td {
            padding: 12px 5px;
            font-size: 0.95rem;
            border-bottom: 1px solid #1a1f2c;
        }
        .diff-tag { text-transform: uppercase; font-weight: bold; }
        .diff-easy { color: #00ffd0; }
        .diff-medium { color: #ffb700; }
        .diff-hard { color: #e10600; }
    </style>
</head>
<body>
    <header class="pit-wall-nav">
        <div class="logo">F1 <span>PIT WALL</span></div>
        <div class="nav-actions">
            <a href="dashboard.php" class="back-btn">RETURN TO PIT WALL</a>
        </div>
    </header>

    <main class="profile-container" style="max-width: 850px; margin: 40px auto; padding: 0 20px;">
        
        <!-- =========================================================================
            NEW: DYNAMIC DRIVER TELEMETRY OVERVIEW SECTION
        ========================================================================= -->
        <div class="telemetry-row">
            <div class="stat-card races">
                <p>Total Grands Prix Started</p>
                <h2><?php echo $total_races; ?> <span>Races</span></h2>
            </div>
            <div class="stat-card accuracy">
                <p>Career Avg Accuracy</p>
                <h2 style="color: #00ffd0;"><?php echo $avg_accuracy; ?><span>%</span></h2>
            </div>
        </div>

        <!-- NEW: VISUAL LAP TIMELINE HISTORY CARD -->
        <div class="history-card">
            <h3>🏁 Personal Lap Record Log</h3>
            <table class="telemetry-table">
                <thead>
                    <tr>
                        <th>Timestamp (Date/Time)</th>
                        <th>Difficulty Tier</th>
                        <th>Accuracy</th>
                        <th>Points Accrued</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($history_results->num_rows > 0) {
                        while($row = $history_results->fetch_assoc()) {
                            // Check loop data to style the tier tag colors cleanly
                            $diff_class = 'diff-medium';
                            $clean_diff = strtolower($row['Qdifficulty']);
                            if ($clean_diff === 'easy') $diff_class = 'diff-easy';
                            if ($clean_diff === 'hard') $diff_class = 'diff-hard';

                            echo "<tr>";
                            echo "<td style='color: #aaa;'>" . date("M d, Y - H:i", strtotime($row['dateCompleted'])) . "</td>";
                            echo "<td><span class='diff-tag " . $diff_class . "'>" . htmlspecialchars($row['Qdifficulty']) . "</span></td>";
                            echo "<td style='color: #fff;'>" . $row['accuracy'] . "%</td>";
                            echo "<td style='color: #00ffd0; font-weight: bold;'>+" . $row['score'] . " pts</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' style='padding: 20px 0; text-align: center; color: #444;'>No tracking diagnostics logged yet. Complete a race session to compile history.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- ========================================================================= -->

        <!-- ORIGINAL DRIVER IDENTIFICATION CREDENTIALS FORM -->
        <section class="card profile-card" style="margin-top: 0;">
            <div class="card-header">
                <h2>DRIVER IDENTIFICATION</h2>
                <p>Update your paddock credentials and team allegiance.</p>
                
                <?php if(!empty($status_msg)): ?>
                    <p style="color: <?php echo ($msg_type === 'success') ? '#00d2be' : '#ff1801'; ?>; margin-top:15px; font-size:1.05rem;">
                        <b><?php echo $status_msg; ?></b>
                    </p>
                <?php endif; ?>
            </div>
            
            <form class="profile-form" method="POST" action="profile.php">
                <div class="form-row">
                    <div class="input-group">
                        <label for="username">Driver Name</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username'] ?? ''); ?>" disabled style="opacity: 0.5; cursor: not-allowed;">
                    </div>
                    <div class="input-group">
                        <label for="email">Comms Channel (Email)</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="input-group full-width">
                    <label for="team">Constructor Allegiance (Favorite Team)</label>
                    <select id="team" name="team">
                        <option value="">-- Select Your Team --</option>  
                        <?php foreach($teams as $team): ?>
                            <option value="<?php echo $team['teamID']; ?>" <?php if(($user_data['teamID'] ?? '') == $team['teamID']) echo 'selected'; ?>><?php echo htmlspecialchars($team['teamName']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="security-section">
                    <h3>SECURITY PROTOCOLS</h3>
                    <div class="form-row">
                        <div class="input-group">
                            <label for="new-password">New Telemetry Key</label>
                            <input type="password" id="new-password" name="new-password" placeholder="Leave blank to keep current">
                        </div>
                        <div class="input-group">
                            <label for="confirm-password">Confirm New Key</label>
                            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm new password">
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="save-btn">BOX CONFIRM - SAVE CHANGES</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>