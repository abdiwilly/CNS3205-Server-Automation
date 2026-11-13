<?php
session_start();
require_once 'db.php';
$message = '';
$msg_type = '';
$logged_user = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $logged_user = $user;
        $message = "Authentication Successful! Welcome, " . $user['username'] . " (" . $user['full_name'] . ").";
        $msg_type = "success";
    } else {
        $message = "Invalid username or password. Please try again.";
        $msg_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IS Project 1 - Student Login (login.php)</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 30px; }
        .container { max-width: 650px; margin: 0 auto; background: #1e293b; padding: 30px; border-radius: 12px; border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.4); }
        .nav { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #334155; display: flex; gap: 15px; }
        .nav a { color: #38bdf8; text-decoration: none; font-weight: 600; padding: 6px 12px; border-radius: 6px; background: #0f172a; }
        .nav a.active { background: #0284c7; color: white; }
        h2 { color: #38bdf8; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; color: #cbd5e1; font-size: 14px; }
        input { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #475569; background: #0f172a; color: white; box-sizing: border-box; }
        button { background: #0284c7; color: white; border: none; padding: 12px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; font-size: 15px; }
        .alert { padding: 14px; border-radius: 6px; margin-bottom: 20px; font-weight: 600; }
        .success { background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #6ee7b7; }
        .error { background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fca5a5; }
        .profile-card { background: #0f172a; border: 1px solid #10b981; border-radius: 8px; padding: 20px; margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="nav">
        <a href="signup.php">1. Signup (signup.php)</a>
        <a href="login.php" class="active">2. Login (login.php)</a>
        <a href="feedback.php">3. Course Feedback Form (feedback.php)</a>
    </div>
    <h2>IS Project 1 — Student Login Portal</h2>
    <p style="color:#94a3b8; font-size:14px;">Authenticating against MySQL Database: <strong><?php echo htmlspecialchars($dbname); ?></strong></p>

    <?php if ($message): ?>
        <div class="alert <?php echo $msg_type; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" required placeholder="Enter your username">
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required placeholder="Enter your password">
        </div>
        <button type="submit">Login to Portal</button>
    </form>

    <?php if ($logged_user): ?>
    <div class="profile-card">
        <h3 style="margin-top:0; color:#10b981;">Active Student Session Verified</h3>
        <p><strong>Student ID:</strong> #<?php echo $logged_user['id']; ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($logged_user['username']); ?></p>
        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($logged_user['full_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($logged_user['email']); ?></p>
        <p><strong>Department:</strong> <?php echo htmlspecialchars($logged_user['department']); ?></p>
        <p><strong>Account Created:</strong> <?php echo $logged_user['created_at']; ?></p>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
