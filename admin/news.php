<?php
require_once '../includes/auth.php';
require_role('admin');
require_once '../includes/db.php';
// Delete news
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Get news title for audit log
    $news_row = $conn->query("SELECT title FROM news WHERE id=$id")->fetch_assoc();
    $conn->query("DELETE FROM news WHERE id=$id");
    // AUDIT LOG
    $user_id = $_SESSION['user_id'];
    $action_type = 'delete';
    $details = 'Deleted news: ' . ($news_row ? $news_row['title'] : '');
    $conn->query("INSERT INTO audit_log (user_id, news_id, action_type, details) VALUES ($user_id, $id, '$action_type', '" . $conn->real_escape_string($details) . "')");
}
$news = $conn->query("SELECT n.*, c.name as category, u.name as author FROM news n LEFT JOIN category c ON n.category_id = c.id LEFT JOIN users u ON n.author_id = u.id ORDER BY n.dateposted DESC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الأخبار</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>إدارة الأخبار</h2>
    <a href="add_news.php" class="btn btn-success mb-3">إضافة خبر</a>
    <table class="table table-bordered">
        <thead><tr><th>الرقم</th><th>العنوان</th><th>القسم</th><th>الكاتب</th><th>تاريخ النشر</th><th>الحالة</th><th>إجراءات</th></tr></thead>
        <tbody>
        <?php while($n = $news->fetch_assoc()): ?>
            <tr>
                <td><?= $n['id'] ?></td>
                <td><?= htmlspecialchars($n['title']) ?></td>
                <td><?= htmlspecialchars($n['category']) ?></td>
                <td><?= htmlspecialchars($n['author']) ?></td>
                <td><?= $n['dateposted'] ?></td>
                <td><?= $n['status'] ?></td>
                <td>
                    <a href="edit_news.php?id=<?= $n['id'] ?>" class="btn btn-info btn-sm">تعديل</a>
                    <a href="?delete=<?= $n['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('تأكيد الحذف؟')">حذف</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php">رجوع للوحة التحكم</a>
</div>
</body>
</html>
