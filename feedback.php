<?php
require_once 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = trim($_POST['student_name'] ?? '');
    $course_unit  = trim($_POST['course_unit'] ?? '');
    $rating       = trim($_POST['rating'] ?? '');
    $comments     = trim($_POST['comments'] ?? '');

    if ($student_name && $course_unit && $rating && $comments) {
        $stmt = $pdo->prepare("INSERT INTO course_feedback (student_name, course_unit, rating, comments) VALUES (?, ?, ?, ?)");
        $stmt->execute([$student_name, $course_unit, $rating, $comments]);
        $message = "Feedback successfully POSTed and saved to CAT2.course_feedback table!";
    }
}

$entries = $pdo->query("SELECT * FROM course_feedback ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IS Project 1 - Course Lab Feedback Form (CAT2 Database)</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 30px; }
        .container { max-width: 750px; margin: 0 auto; background: #1e293b; padding: 30px; border-radius: 12px; border: 1px solid #334155; box-shadow: 0 10px 25px rgba(0,0,0,0.4); }
        .nav { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #334155; display: flex; gap: 15px; }
        .nav a { color: #38bdf8; text-decoration: none; font-weight: 600; padding: 6px 12px; border-radius: 6px; background: #0f172a; }
        .nav a.active { background: #0284c7; color: white; }
        h2 { color: #38bdf8; margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; color: #cbd5e1; font-size: 14px; }
        input, select, textarea { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #475569; background: #0f172a; color: white; box-sizing: border-box; }
        button { background: #8b5cf6; color: white; border: none; padding: 12px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; font-size: 15px; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #6ee7b7; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; margin-top: 25px; font-size: 14px; }
        th, td { border: 1px solid #334155; padding: 10px; text-align: left; }
        th { background: #0f172a; color: #38bdf8; }
    </style>
</head>
<body>
<div class="container">
    <div class="nav">
        <a href="signup.php">1. Signup (signup.php)</a>
        <a href="login.php">2. Login (login.php)</a>
        <a href="feedback.php" class="active">3. Course Feedback Form (feedback.php)</a>
    </div>
    <h2>CNS 3205 Server Administration — Lab Feedback Form</h2>
    <p style="color:#94a3b8; font-size:14px;">Table: <strong>CAT2.course_feedback</strong> (POST & Retrieve Verification)</p>

    <?php if ($message): ?>
        <div class="alert"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" action="feedback.php">
        <div class="form-group">
            <label>Student Username / Name:</label>
            <input type="text" name="student_name" required placeholder="e.g., Abdi (169910) / studentCNSA">
        </div>
        <div class="form-group">
            <label>Course Unit / Module:</label>
            <input type="text" name="course_unit" value="CNS 3205 - Server Administration (LAMP & Cloud)" required>
        </div>
        <div class="form-group">
            <label>Lab Experience Rating:</label>
            <select name="rating">
                <option value="5/5 - Excellent">5/5 - Excellent</option>
                <option value="4/5 - Very Good">4/5 - Very Good</option>
                <option value="3/5 - Good">3/5 - Good</option>
            </select>
        </div>
        <div class="form-group">
            <label>Technical Comments / Lab Reflection:</label>
            <textarea name="comments" rows="3" required placeholder="Enter your feedback on hosting PHP & MySQL on Ubuntu..."></textarea>
        </div>
        <button type="submit">Submit Feedback (HTTP POST to CAT2)</button>
    </form>

    <h3 style="margin-top:30px; color:#94a3b8;">Retrieved Records from `CAT2.course_feedback` (<?php echo count($entries); ?>)</h3>
    <table>
        <tr><th>ID</th><th>Student</th><th>Course Unit</th><th>Rating</th><th>Comments</th><th>Timestamp</th></tr>
        <?php foreach ($entries as $row): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><strong><?php echo htmlspecialchars($row['student_name']); ?></strong></td>
            <td><?php echo htmlspecialchars($row['course_unit']); ?></td>
            <td><?php echo htmlspecialchars($row['rating']); ?></td>
            <td><?php echo htmlspecialchars($row['comments']); ?></td>
            <td><?php echo $row['submitted_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
