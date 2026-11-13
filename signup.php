<?php
require_once 'db.php';
$message = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = trim($_POST['username'] ?? '');
    $full_name  = trim($_POST['full_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $password   = $_POST['password'] ?? '';

    if ($username && $full_name && $email && $password) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, full_name, email, department, password_hash) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $full_name, $email, $department, $hash]);
            $message = "User '$username' ($full_name) registered successfully in database '$dbname'!";
            $msg_type = "success";
        } catch (PDOException $e) {
            $message = "Registration Error: " . $e->getMessage();
            $msg_type = "error";
        }
    } else {
        $message = "Please fill in all required fields.";
        $msg_type = "error";
    }
}

$users = $pdo->query("SELECT id, username, full_name, email, department, created_at FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IS Project 1 - Student Signup (register.php / signup.php)</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 30px; }
        .container { max-width: 750px; margin: 0 auto; background: #1e293b; padding: 30px; border-radius: 12px; border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.4); }
        .nav { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #334155; display: flex; gap: 15px; }
        .nav a { color: #38bdf8; text-decoration: none; font-weight: 600; padding: 6px 12px; border-radius: 6px; background: #0f172a; }
        .nav a.active { background: #0284c7; color: white; }
        h2 { color: #38bdf8; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; color: #cbd5e1; font-size: 14px; }
        input, select { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #475569; background: #0f172a; color: white; box-sizing: border-box; }
        button { background: #10b981; color: white; border: none; padding: 12px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; font-size: 15px; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: 600; }
        .success { background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #6ee7b7; }
        .error { background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fca5a5; }
        table { width: 100%; border-collapse: collapse; margin-top: 25px; font-size: 14px; }
        th, td { border: 1px solid #334155; padding: 10px; text-align: left; }
        th { background: #0f172a; color: #38bdf8; }
    </style>
</head>
<body>
<div class="container">
    <div class="nav">
        <a href="signup.php" class="active">1. Signup (signup.php)</a>
        <a href="login.php">2. Login (login.php)</a>
        <a href="feedback.php">3. Course Feedback Form (feedback.php)</a>
    </div>
    <h2>IS Project 1 — Student Registration Portal</h2>
    <p style="color:#94a3b8; font-size:14px;">Connected to MySQL Database: <strong><?php echo htmlspecialchars($dbname); ?></strong></p>

    <?php if ($message): ?>
        <div class="alert <?php echo $msg_type; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" action="signup.php">
        <div class="form-group">
            <label>Username (e.g., studentCNSA, studentCNSB, studentMiguel, studentHaggai):</label>
            <input type="text" name="username" required placeholder="Enter student username">
        </div>
        <div class="form-group">
            <label>Full Name:</label>
            <input type="text" name="full_name" required placeholder="Enter full name">
        </div>
        <div class="form-group">
            <label>Email Address:</label>
            <input type="email" name="email" required placeholder="student@strathmore.edu">
        </div>
        <div class="form-group">
            <label>Department / Group:</label>
            <select name="department">
                <option value="CNS Group A">CNS Group A</option>
                <option value="CNS Group B">CNS Group B</option>
                <option value="Information Systems (IS)">Information Systems (IS)</option>
            </select>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" required placeholder="Enter password">
        </div>
        <button type="submit">Register Student Account</button>
    </form>

    <h3 style="margin-top:30px; color:#94a3b8;">Registered Users in Database (<?php echo count($users); ?>)</h3>
    <table>
        <tr><th>ID</th><th>Username</th><th>Full Name</th><th>Email</th><th>Department</th><th>Registered At</th></tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?php echo $u['id']; ?></td>
            <td><strong><?php echo htmlspecialchars($u['username']); ?></strong></td>
            <td><?php echo htmlspecialchars($u['full_name']); ?></td>
            <td><?php echo htmlspecialchars($u['email']); ?></td>
            <td><?php echo htmlspecialchars($u['department']); ?></td>
            <td><?php echo $u['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
