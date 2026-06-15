<?php
session_start();
require 'db.php';

// Authentication Check
if (!isset($_SESSION['user_id'])) {
    header("Location: admin-login.php");
    exit();
}

$message = "";

// Handle CRUD Operations (Targeting your actual local tables)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    // --- DRIVER/USER CRUD ---
    if ($action === 'delete_user') {
        $id = (int)($_POST['id'] ?? $_POST['playerID'] ?? 0);
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?") ?: $conn->prepare("DELETE FROM users WHERE playerID = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) { $message = "Driver removed from the grid."; }
    } 
    elseif ($action === 'add_user') {
        $username = $_POST['username'] ?? $_POST['Username'] ?? '';
        $email = $_POST['email'] ?? $_POST['Email'] ?? '';
        // FIX: Hash the password securely so administrators don't save plaintext keys
        $password = password_hash($_POST['password'] ?? $_POST['Password'] ?? '', PASSWORD_DEFAULT);
        $team = $_POST['team'] ?? $_POST['teamID'] ?? 'unassigned';

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, teamID, super_license_points) VALUES (?, ?, ?, ?, 0)") ?:
                $conn->prepare("INSERT INTO users (Username, Email, Password, teamID, points) VALUES (?, ?, ?, ?, 0)");
        
        $stmt->bind_param("ssss", $username, $email, $password, $team);
        if ($stmt->execute()) { $message = "New driver added to the grid."; }
    } 
    elseif ($action === 'edit_user') {
        $id = (int)($_POST['id'] ?? $_POST['playerID'] ?? 0);
        $username = $_POST['username'] ?? $_POST['Username'] ?? '';
        $email = $_POST['email'] ?? $_POST['Email'] ?? '';
        $team = $_POST['team'] ?? $_POST['teamID'] ?? '';
        $points = (int)($_POST['points'] ?? 0);

        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, teamID=?, super_license_points=? WHERE id=?") ?:
                $conn->prepare("UPDATE users SET Username=?, Email=?, teamID=?, points=? WHERE playerID=?");
        
        $stmt->bind_param("sssii", $username, $email, $team, $points, $id);
        if ($stmt->execute()) { $message = "Driver telemetry successfully updated."; }
    }

    // --- QUESTION CRUD ---
    if ($action === 'delete_question') {
        $id = (int)($_POST['id'] ?? $_POST['questionID'] ?? 0);
        $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?") ?: $conn->prepare("DELETE FROM questions WHERE questionID = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) { $message = "Question removed from telemetry."; }
    } 
    elseif ($action === 'add_question') {
        $qText = $_POST['questionText'] ?? $_POST['question'] ?? '';
        $ansA = $_POST['answerA'] ?? $_POST['option_a'] ?? '';
        $ansB = $_POST['answerB'] ?? $_POST['option_b'] ?? '';
        $ansC = $_POST['answerC'] ?? $_POST['option_c'] ?? '';
        $ansD = $_POST['answerD'] ?? $_POST['option_d'] ?? ''; // FIX: Capture Option D values
        $ansRight = $_POST['answerRight'] ?? $_POST['correct_answer'] ?? '';
        $diff = $_POST['difficultyLevel'] ?? $_POST['difficulty'] ?? 'Medium';

        // FIX: Included option_d field inside structural inserts, matching binding strings
        $stmt = $conn->prepare("INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_answer, difficulty) VALUES (?, ?, ?, ?, ?, ?, ?)") ?:
                $conn->prepare("INSERT INTO questions (questionText, answerA, answerB, answerC, answerD, answerRight, difficultyLevel) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssssss", $qText, $ansA, $ansB, $ansC, $ansD, $ansRight, $diff);
        if ($stmt->execute()) { $message = "New telemetry question initialized."; }
    } 
    elseif ($action === 'edit_question') {
        $id = (int)($_POST['id'] ?? $_POST['questionID'] ?? 0);
        $qText = $_POST['questionText'] ?? $_POST['question'] ?? '';
        $ansA = $_POST['answerA'] ?? $_POST['option_a'] ?? '';
        $ansB = $_POST['answerB'] ?? $_POST['option_b'] ?? '';
        $ansC = $_POST['answerC'] ?? $_POST['option_c'] ?? '';
        $ansD = $_POST['answerD'] ?? $_POST['option_d'] ?? '';
        $ansRight = $_POST['answerRight'] ?? $_POST['correct_answer'] ?? '';
        $diff = $_POST['difficultyLevel'] ?? $_POST['difficulty'] ?? 'Medium';

        $stmt = $conn->prepare("UPDATE questions SET question=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_answer=?, difficulty=? WHERE id=?") ?:
                $conn->prepare("UPDATE questions SET questionText=?, answerA=?, answerB=?, answerC=?, answerD=?, answerRight=?, difficultyLevel=? WHERE questionID=?");
        
        $stmt->bind_param("sssssssi", $qText, $ansA, $ansB, $ansC, $ansD, $ansRight, $diff, $id);
        if ($stmt->execute()) { $message = "Telemetry question data updated."; }
    }
}

// --- SAFE PRODUCTION QUERIES ---
$users = $conn->query("
    SELECT u.*, t.teamName, t.teamColor 
    FROM users u 
    LEFT JOIN team t ON u.teamID = t.teamID 
    ORDER BY u.id DESC
") ?: $conn->query("
    SELECT p.*, t.teamName, t.teamColor 
    FROM users p 
    LEFT JOIN Team t ON p.teamID = t.teamID 
    ORDER BY p.playerID DESC
");

$questions = $conn->query("SELECT * FROM questions ORDER BY id DESC") ?: $conn->query("SELECT * FROM questions ORDER BY questionID DESC");

// Fetch Metrics Counters
$total_users = 0; $total_questions = 0;
if($res = $conn->query("SELECT COUNT(*) as count FROM users")) { $total_users = $res->fetch_assoc()['count']; }
if($res = $conn->query("SELECT COUNT(*) as count FROM questions")) { $total_questions = $res->fetch_assoc()['count']; }

// Fetch Teams for dropdown dynamic selections
$teams_list = [];
if($team_res = $conn->query("SELECT teamID, teamName FROM Team ORDER BY teamName ASC")) {
    while($row = $team_res->fetch_assoc()) {
        $teams_list[] = $row;
    }
}

// Activity Logs Fallback Wrapper
$activity_logs = $conn->query("SELECT u.username, a.action, a.timestamp FROM activity_logs a JOIN users u ON a.user_id = u.id ORDER BY a.timestamp DESC LIMIT 10") ?:
                 $conn->query("SELECT p.Username, ua.actionType, ua.timeStamp FROM UserAction ua JOIN Player p ON ua.playerID = p.playerID ORDER BY ua.timeStamp DESC LIMIT 10") ?: false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1 Grid Quiz - Admin Pit Wall</title>
    <link rel="stylesheet" href="css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/admin-dashboard.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="pit-wall-nav">
        <div class="logo">F1 <span>ADMIN PIT WALL</span></div>
        <div class="driver-profile">
            <span class="status-indicator" style="background-color: var(--f1-red);"></span>
            <span style="color:var(--text-light); margin-right:15px;">DIRECTOR</span>
            <a href="admin-login.php" style="color:var(--text-muted); text-decoration:none; font-size:0.8rem;">[ LOGOUT ]</a>
        </div>
    </header>

    <?php if($message): ?>
        <div class="message-banner"><?php echo $message; ?></div>
    <?php endif; ?>

    <main class="dashboard-container">
        
        <section class="card" style="display: flex; gap: 20px; flex-direction: row; background: transparent; box-shadow: none; border: none; margin-bottom: -10px;">
            <div class="card" style="flex: 1; padding: 20px; text-align: center; border-left: 4px solid var(--success-cyan);">
                <h3 style="color: var(--text-muted);">TOTAL DRIVERS</h3>
                <h1 style="font-size: 2.5rem; color: var(--text-light);"><?php echo $total_users; ?></h1>
            </div>
            <div class="card" style="flex: 1; padding: 20px; text-align: center; border-left: 4px solid var(--f1-red);">
                <h3 style="color: var(--text-muted);">TOTAL QUESTIONS</h3>
                <h1 style="font-size: 2.5rem; color: var(--text-light);"><?php echo $total_questions; ?></h1>
            </div>
        </section>
        
        <!-- MANAGE DRIVERS SECTION -->
        <section class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div class="clickable-title" onclick="toggleSection('usersContainer')">
                    <h2 style="margin:0;">MANAGE DRIVERS</h2>
                </div>
                <div class="premium-search-wrapper">
                    <input type="text" id="searchUsers" class="premium-search-input" placeholder="Search drivers..." onkeyup="filterTable('searchUsers', 'usersTable')">
                </div>
            </div>
            
            <div id="usersContainer">
                <form class="admin-form" method="POST">
                    <input type="hidden" name="action" value="add_user">
                    <input type="text" name="username" placeholder="Driver Username" required>
                    <input type="email" name="email" placeholder="Comms Channel (Email)" required>
                    <input type="password" name="password" placeholder="Telemetry Key (Password)" required>
                    
                    <select name="team" required style="background: #1a191f; color: #fff; border: 1px solid #333; padding: 10px; border-radius: 4px;">
                        <option value="" disabled selected>Select Constructor</option>
                        <?php foreach($teams_list as $t_opt): ?>
                            <option value="<?php echo $t_opt['teamID']; ?>"><?php echo htmlspecialchars($t_opt['teamName']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    
                    <button type="submit" class="admin-btn">ADD DRIVER</button>
                </form>
                
                <div class="admin-table-container">
                    <table id="usersTable">
                        <thead>
                            <tr><th>ID</th><th>Driver Name</th><th>Email</th><th>Constructor</th><th>Points</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php while($u = $users->fetch_assoc()): 
                                $uID   = $u['id'] ?? $u['playerID'] ?? 0;
                                $uName = $u['username'] ?? $u['Username'] ?? '';
                                $uMail = $u['email'] ?? $u['Email'] ?? '';
                                
                                $uTeam = $u['teamName'] ?? $u['team'] ?? $u['teamID'] ?? 'Unassigned';
                                $uColor = (!empty($u['teamColor'])) ? $u['teamColor'] : 'var(--success-cyan)';
                                
                                $uPts  = $u['super_license_points'] ?? $u['points'] ?? 0;
                                $rawTeamID = $u['team'] ?? $u['teamID'] ?? '';
                            ?>
                            <tr>
                                <td><?php echo $uID; ?></td>
                                <td><?php echo htmlspecialchars($uName); ?></td>
                                <td><?php echo htmlspecialchars($uMail); ?></td>
                                <td><span style="text-transform: uppercase; font-weight: bold; color: <?php echo htmlspecialchars($uColor); ?>;"><?php echo htmlspecialchars($uTeam); ?></span></td>
                                <td><?php echo $uPts; ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="admin-btn" style="background: var(--success-cyan); color: #000;" 
                                            onclick="openUserEdit(<?php echo $uID; ?>, '<?php echo htmlspecialchars(addslashes($uName)); ?>', '<?php echo htmlspecialchars(addslashes($uMail)); ?>', '<?php echo htmlspecialchars(addslashes($rawTeamID)); ?>', <?php echo $uPts; ?>)">EDIT</button>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="delete_user">
                                            <input type="hidden" name="id" value="<?php echo $uID; ?>">
                                            <button type="submit" class="admin-btn danger" onclick="return confirm('Black flag this driver?');">FLAG</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- MANAGE TELEMETRY QUESTIONS SECTION -->
<section class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div class="clickable-title" onclick="toggleSection('questionsContainer')">
            <h2 style="margin:0;">MANAGE TELEMETRY (QUESTIONS)</h2>
        </div>
        <div class="premium-search-wrapper">
            <input type="text" id="searchQuestions" class="premium-search-input" placeholder="Search questions or answers..." onkeyup="filterTable('searchQuestions', 'questionsTable')">
        </div>
    </div>
    
    <div id="questionsContainer">
        <form class="admin-form" method="POST">
            <input type="hidden" name="action" value="add_question">
            <input type="text" name="question" class="input-wide" placeholder="Enter Full Question Text" required>
            <input type="text" name="option_a" placeholder="Option A" required>
            <input type="text" name="option_b" placeholder="Option B" required>
            <input type="text" name="option_c" placeholder="Option C" required>
            <input type="text" name="option_d" placeholder="Option D" required>
            <input type="text" name="correct_answer" class="input-answer" placeholder="Exact Correct Answer" required>
            <select name="difficulty">
                <option value="Easy">Easy</option>
                <option value="Medium" selected>Medium</option>
                <option value="Hard">Hard</option>
            </select>
            <button type="submit" class="admin-btn">DEPLOY QUESTION</button>
        </form>
        
        <div class="admin-table-container">
            <table id="questionsTable">
                <thead>
                    <tr><th>ID</th><th>Difficulty</th><th>Question</th><th>Correct Answer</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php while($q = $questions->fetch_assoc()): 
                        $qID   = $q['id'] ?? $q['questionID'] ?? 0;
                        $qText = $q['question'] ?? $q['questionText'] ?? '';
                        $qA    = $q['option_a'] ?? $q['answerA'] ?? '';
                        $qB    = $q['option_b'] ?? $q['answerB'] ?? '';
                        $qC    = $q['option_c'] ?? $q['answerC'] ?? '';
                        $qD    = $q['option_d'] ?? $q['answerD'] ?? '';
                        $qAns  = $q['correct_answer'] ?? $q['answerRight'] ?? '';
                        $qDiff = $q['difficulty'] ?? $q['difficultyLevel'] ?? 'Medium';
                    ?>
                    <tr>
                        <td><?php echo $qID; ?></td>
                        <td><span style="text-transform: capitalize; color: var(--text-muted); font-size:0.85rem;"><?php echo htmlspecialchars($qDiff); ?></span></td>
                        <td><?php echo htmlspecialchars($qText); ?></td>
                        <!-- FIXED: Placed the class cleanly inside the td element wrapper -->
                        <td class="answer-text"><?php echo htmlspecialchars($qAns); ?></td>
                        <td>
                            <div class="action-buttons">
                                <button type="button" class="admin-btn" style="background: var(--success-cyan); color: #000;" 
                                    onclick="openQuestionEdit(
                                        <?php echo $qID; ?>, 
                                        '<?php echo htmlspecialchars(addslashes($qText)); ?>', 
                                        '<?php echo htmlspecialchars(addslashes($qA)); ?>', 
                                        '<?php echo htmlspecialchars(addslashes($qB)); ?>', 
                                        '<?php echo htmlspecialchars(addslashes($qC)); ?>', 
                                        '<?php echo htmlspecialchars(addslashes($qD)); ?>',
                                        '<?php echo htmlspecialchars(addslashes($qAns)); ?>',
                                        '<?php echo htmlspecialchars(addslashes($qDiff)); ?>'
                                    )">EDIT</button>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete_question">
                                    <input type="hidden" name="id" value="<?php echo $qID; ?>">
                                    <button type="submit" class="admin-btn danger" onclick="return confirm('Delete this question?');">DEL</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

        <!-- FIX: Relocated Driver Activity Log container block directly to the bottom area -->
        <?php if($activity_logs): ?>
        <section class="card">
            <div class="card-header"><h2>DRIVER ACTIVITY LOG</h2></div>
            <div class="admin-table-container">
                <table>
                    <thead><tr><th>Timestamp</th><th>Driver</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php while($log = $activity_logs->fetch_assoc()): 
                            $logTime = $log['timestamp'] ?? $log['timeStamp'] ?? '';
                            $logUser = $log['username'] ?? $log['Username'] ?? '';
                            $logAct  = $log['action'] ?? $log['actionType'] ?? '';
                        ?>
                        <tr>
                            <td style="color: var(--text-muted); font-size: 0.85rem;"><?php echo $logTime; ?></td>
                            <td style="color: var(--success-cyan); font-weight: bold;"><?php echo htmlspecialchars($logUser); ?></td>
                            <td><?php echo htmlspecialchars($logAct); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <?php endif; ?>

    </main>

    <!-- MODAL POPUPS -->
    <div id="userEditModal" class="modal-overlay">
        <div class="modal-box">
            <h2>UPDATE DRIVER TELEMETRY</h2>
            <form class="admin-form" method="POST" style="margin-bottom: 0;">
                <input type="hidden" name="action" value="edit_user">
                <input type="hidden" name="id" id="edit_user_id">
                <input type="text" name="username" id="edit_username" required placeholder="Username">
                <input type="email" name="email" id="edit_email" required placeholder="Email">
                
                <select name="team" id="edit_team" required style="background: #1a191f; color: #fff; border: 1px solid #333; padding: 10px; border-radius: 4px; width: 100%;">
                    <option value="" disabled>Select Constructor</option>
                    <?php foreach($teams_list as $t_opt): ?>
                        <option value="<?php echo $t_opt['teamID']; ?>"><?php echo htmlspecialchars($t_opt['teamName']); ?></option>
                    <?php endforeach; ?>
                </select>
                
                <input type="number" name="points" id="edit_points" required placeholder="Points">
                <div class="action-buttons input-wide">
                    <button type="submit" class="admin-btn" style="background: var(--success-cyan); color:#000;">SAVE CHANGES</button>
                    <button type="button" class="admin-btn danger" onclick="closeModals()">CANCEL</button>
                </div>
            </form>
        </div>
    </div>

    <div id="questionEditModal" class="modal-overlay">
        <div class="modal-box">
            <h2>UPDATE TRACK TELEMETRY (QUESTION)</h2>
            <form class="admin-form" method="POST" style="margin-bottom: 0;">
                <input type="hidden" name="action" value="edit_question">
                <input type="hidden" name="id" id="edit_q_id">
                <input type="text" name="question" id="edit_q_text" class="input-wide" required placeholder="Question Text">
                <input type="text" name="option_a" id="edit_q_a" required placeholder="Option A">
                <input type="text" name="option_b" id="edit_q_b" required placeholder="Option B">
                <input type="text" name="option_c" id="edit_q_c" required placeholder="Option C">
                <input type="text" name="option_d" id="edit_q_d" required placeholder="Option D">
                <input type="text" name="correct_answer" id="edit_q_ans" class="input-answer" required placeholder="Correct Answer">
                <select name="difficulty" id="edit_q_diff" required>
                    <option value="Easy">Easy</option>
                    <option value="Medium">Medium</option>
                    <option value="Hard">Hard</option>
                </select>
                <div class="action-buttons input-wide">
                    <button type="submit" class="admin-btn" style="background: var(--success-cyan); color:#000;">SAVE CHANGES</button>
                    <button type="button" class="admin-btn danger" onclick="closeModals()">CANCEL</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openUserEdit(id, username, email, teamId, points) {
            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_team').value = teamId;
            document.getElementById('edit_points').value = points;
            document.getElementById('userEditModal').style.display = 'flex';
        }

        function openQuestionEdit(id, q, a, b, c, d, ans, diff) {
            document.getElementById('edit_q_id').value = id;
            document.getElementById('edit_q_text').value = q;
            document.getElementById('edit_q_a').value = a;
            document.getElementById('edit_q_b').value = b;
            document.getElementById('edit_q_c').value = c;
            document.getElementById('edit_q_d').value = d;
            document.getElementById('edit_q_ans').value = ans;
            document.getElementById('edit_q_diff').value = diff;
            document.getElementById('questionEditModal').style.display = 'flex';
        }

        function closeModals() {
            document.getElementById('userEditModal').style.display = 'none';
            document.getElementById('questionEditModal').style.display = 'none';
        }

        function toggleSection(containerId) {
            const container = document.getElementById(containerId);
            container.style.display = (container.style.display === 'none' || container.style.display === '') ? 'block' : 'none';
        }

        function filterTable(inputId, tableId) {
            const input = document.getElementById(inputId);
            const filter = input.value.toLowerCase();
            const table = document.getElementById(tableId);
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let displayRow = false;
                const tds = tr[i].getElementsByTagName('td');
                
                for (let j = 0; j < tds.length - 1; j++) {
                    if (tds[j]) {
                        const txtValue = tds[j].textContent || tds[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            displayRow = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = displayRow ? "" : "none";
            }
        }
    </script>
</body>
</html>