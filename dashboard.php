<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Get current user data, their team information, and dynamic Rank Name from Rank table
$user_query = "
    SELECT u.username, u.super_license_points, t.teamName, t.teamColor,
           (SELECT r.rankName 
            FROM Rank r 
            WHERE u.super_license_points >= r.minPoints 
            ORDER BY r.minPoints DESC LIMIT 1) as player_rank
    FROM users u
    LEFT JOIN Team t ON u.teamID = t.teamID
    WHERE u.id = ?
";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();

// Fallback if no rank matches the criteria
$player_rank = $user_data['player_rank'] ?? "UNRANKED";

// 2. Get top 10 leaderboard joined with Team table for hex colors
$leaderboard_query = "
    SELECT u.id, u.username, u.super_license_points, t.teamName, t.teamColor 
    FROM users u 
    LEFT JOIN Team t ON u.teamID = t.teamID 
    ORDER BY u.super_license_points DESC 
    LIMIT 10
";
$leaderboard_result = $conn->query($leaderboard_query);

// 3. Determine user's grid rank position
$rank_query = "SELECT COUNT(*) as rank FROM users WHERE super_license_points > ?";
$rank_stmt = $conn->prepare($rank_query);
$rank_stmt->bind_param("i", $user_data['super_license_points']);
$rank_stmt->execute();
$rank_data = $rank_stmt->get_result()->fetch_assoc();
$current_rank = "P" . ($rank_data['rank'] + 1);

// 4. Constructor Standings (Aggregated Team Scores using Team relational table)
$team_standings_query = "
    SELECT t.teamName, t.teamColor, SUM(u.super_license_points) as team_points 
    FROM users u 
    INNER JOIN Team t ON u.teamID = t.teamID 
    GROUP BY t.teamID, t.teamName, t.teamColor 
    ORDER BY team_points DESC
";
$team_standings_result = $conn->query($team_standings_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 Grid Quiz - Pit Wall</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="icon" type="image/x-icon" href="media/logo.ico">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="pit-wall-nav">
        <div class="logo">F1 <span>PIT WALL</span></div>
        <div class="driver-profile">
            <span class="status-indicator"></span>
            <a href="profile.php" class="profile-link">Driver: <span class="username"><?php echo htmlspecialchars($user_data['username'] ?? ''); ?></span></a>
            <a href="landing_page.php" style="color:var(--text-muted); text-decoration:none; margin-left:15px; font-size:0.8rem;">[ LOGOUT ]</a>
        </div>
    </header>

    <main class="dashboard-container">
        
        <section class="card telemetry-card">
            <div class="card-header">
                <h2>LIVE TELEMETRY</h2>
            </div>
            <div class="score-display" style="flex-wrap: wrap;">
                <div class="data-point">
                    <span class="label">DRIVER RANK</span>
                    <span class="value" style="color: var(--success-cyan); font-size: 1.8rem;"><?php echo htmlspecialchars($player_rank); ?></span>
                </div>
                <div class="data-point">
                    <span class="label">TOTAL SCORE (PTS)</span>
                    <span class="value"><?php echo $user_data['super_license_points'] ?? 0; ?></span>
                </div>
                <div class="data-point">
                    <span class="label">CURRENT RANK</span>
                    <span class="value"><?php echo $current_rank; ?></span>
                </div>
                <div class="data-point" style="width: 100%; margin-top: 10px; flex: none;">
                    <span class="label">CONSTRUCTOR PROFILE</span>
                    <span class="value" style="font-size: 1.2rem; text-transform: uppercase;">
                        <span class="team-dot" style="background-color: <?php echo htmlspecialchars($user_data['teamColor'] ?? '#ccc'); ?>;"></span>
                        <?php echo htmlspecialchars($user_data['teamName'] ?? 'No Team'); ?>
                    </span>
                </div>
            </div>
            <button class="start-quiz-btn" onclick="window.location.href='index.php'">LIGHTS OUT - START QUIZ</button>
        </section>

        <section class="card standings-card">
            <div class="card-header">
                <h2>DRIVER STANDINGS</h2>
            </div>
            <table class="standings-table">
                <thead>
                    <tr>
                        <th>Pos</th>
                        <th>Driver</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $pos = 1;
                    while($row = $leaderboard_result->fetch_assoc()): 
                        $is_current = ($row['id'] == $user_id) ? 'class="current-user"' : '';
                    ?>
                    <tr <?php echo $is_current; ?>>
                        <td><?php echo $pos; ?></td>
                        <td>
                            <span class="team-dot" style="background-color: <?php echo htmlspecialchars($row['teamColor'] ?? '#ccc'); ?>;"></span>
                            <?php echo htmlspecialchars($row['username']); ?>
                        </td>
                        <td><?php echo $row['super_license_points']; ?></td>
                    </tr>
                    <?php 
                    $pos++;
                    endwhile; 
                    ?>
                </tbody>
            </table>
        </section>

        <section class="card standings-card">
            <div class="card-header">
                <h2>CONSTRUCTORS' CHAMPIONSHIP</h2>
            </div>
            <table class="standings-table">
                <thead>
                    <tr><th>Pos</th><th>Constructor</th><th>Total Points</th></tr>
                </thead>
                <tbody>
                    <?php 
                    $t_pos = 1;
                    while($t_row = $team_standings_result->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><?php echo $t_pos++; ?></td>
                        <td style="text-transform: capitalize;">
                            <span class="team-dot" style="background-color: <?php echo htmlspecialchars($t_row['teamColor'] ?? '#ccc'); ?>;"></span>
                            <?php echo htmlspecialchars($t_row['teamName']); ?>
                        </td>
                        <td style="font-weight: bold; color: var(--success-cyan);"><?php echo $t_row['team_points']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

    </main>
</body>
</html>