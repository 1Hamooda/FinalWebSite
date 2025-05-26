<?php
require_once '../includes/auth.php';
require_role('admin');
require_once '../includes/db.php';
$audit = $conn->query("SELECT l.*, u.name as user_name, n.title as news_title FROM audit_log l LEFT JOIN users u ON l.user_id = u.id LEFT JOIN news n ON l.news_id = n.id ORDER BY l.timestamp DESC LIMIT 100");
// Ensure the result set pointer is at the beginning
$audit->data_seek(0);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سجل التعديلات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>سجل التعديلات</h2>
    <table class="table table-bordered">
        <thead><tr><th>المستخدم</th><th>العملية</th><th>الخبر</th><th>التاريخ</th><th>تفاصيل</th></tr></thead>
        <tbody>
        <?php while($row = $audit->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['action_type']) ?></td>
                <td><?= htmlspecialchars($row['news_title']) ?></td>
                <td><?= $row['timestamp'] ?></td>
                <td><?= htmlspecialchars($row['details']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php">رجوع للوحة التحكم</a>
</div>
</body>
</html>
