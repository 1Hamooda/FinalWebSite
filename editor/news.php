<?php
require_once '../includes/auth.php';
require_role('editor');
require_once '../includes/db.php';
// Approve/reject news
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $conn->query("UPDATE news SET status='published' WHERE id=$id");
    // AUDIT LOG
    $user_id = $_SESSION['user_id'];
    $action_type = 'approve';
    $details = 'Editor approved news ID: ' . $id;
    $conn->query("INSERT INTO audit_log (user_id, news_id, action_type, details) VALUES ($user_id, $id, '$action_type', '" . $conn->real_escape_string($details) . "')");
}
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    $conn->query("UPDATE news SET status='rejected' WHERE id=$id");
    // AUDIT LOG
    $user_id = $_SESSION['user_id'];
    $action_type = 'reject';
    $details = 'Editor rejected news ID: ' . $id;
    $conn->query("INSERT INTO audit_log (user_id, news_id, action_type, details) VALUES ($user_id, $id, '$action_type', '" . $conn->real_escape_string($details) . "')");
}
$news = $conn->query("SELECT n.*, c.name as category, u.name as author FROM news n LEFT JOIN category c ON n.category_id = c.id LEFT JOIN users u ON n.author_id = u.id WHERE n.status='pending' ORDER BY n.dateposted DESC");
?>
<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <title>مراجعة الأخبار</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
<div class='container mt-4'>
    <h2>مراجعة الأخبار</h2>
    <table class='table table-bordered'>
        <thead><tr><th>الرقم</th><th>العنوان</th><th>القسم</th><th>الكاتب</th><th>تاريخ النشر</th><th>إجراءات</th></tr></thead>
        <tbody>
        <?php while($n = $news->fetch_assoc()): ?>
            <tr>
                <td><?= $n['id'] ?></td>
                <td><?= htmlspecialchars($n['title']) ?></td>
                <td><?= htmlspecialchars($n['category']) ?></td>
                <td><?= htmlspecialchars($n['author']) ?></td>
                <td><?= $n['dateposted'] ?></td>
                <td>
                    <a href='?approve=<?= $n['id'] ?>' class='btn btn-success btn-sm'>نشر</a>
                    <a href='?reject=<?= $n['id'] ?>' class='btn btn-danger btn-sm'>رفض</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href='index.php'>رجوع للوحة التحكم</a>
</div>
</body>
</html>
