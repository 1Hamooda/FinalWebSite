<?php
require_once '../includes/auth.php';
require_role('author');
require_once '../includes/db.php';
$author_id = $_SESSION['user_id'];
// Delete own news (pending only)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM news WHERE id=$id AND author_id=$author_id AND status='pending'");
}
$news = $conn->query("SELECT n.*, c.name as category FROM news n LEFT JOIN category c ON n.category_id = c.id WHERE n.author_id=$author_id ORDER BY n.dateposted DESC");
?>
<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <title>أخباري</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
<div class='container mt-4'>
    <h2>أخباري</h2>
    <a href='add_news.php' class='btn btn-success mb-3'>إضافة خبر</a>
    <table class='table table-bordered'>
        <thead><tr><th>الرقم</th><th>العنوان</th><th>القسم</th><th>تاريخ النشر</th><th>الحالة</th><th>إجراءات</th></tr></thead>
        <tbody>
        <?php while($n = $news->fetch_assoc()): ?>
            <tr>
                <td><?= $n['id'] ?></td>
                <td><?= htmlspecialchars($n['title']) ?></td>
                <td><?= htmlspecialchars($n['category']) ?></td>
                <td><?= $n['dateposted'] ?></td>
                <td><?= $n['status'] ?></td>
                <td>
                    <?php if ($n['status'] == 'pending'): ?>
                        <a href='edit_news.php?id=<?= $n['id'] ?>' class='btn btn-info btn-sm'>تعديل</a>
                        <a href='?delete=<?= $n['id'] ?>' class='btn btn-danger btn-sm' onclick="return confirm('تأكيد الحذف؟')">حذف</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href='index.php'>رجوع للوحة التحكم</a>
</div>
</body>
</html>
