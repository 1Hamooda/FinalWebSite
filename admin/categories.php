<?php
require_once '../includes/auth.php';
require_role('admin');
require_once '../includes/db.php';
// Delete category
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM category WHERE id=$id");
}
$categories = $conn->query("SELECT * FROM category");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الأقسام</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>إدارة الأقسام</h2>
    <a href="add_category.php" class="btn btn-success mb-3">إضافة قسم</a>
    <table class="table table-bordered">
        <thead><tr><th>الرقم</th><th>الاسم</th><th>الوصف</th><th>إجراءات</th></tr></thead>
        <tbody>
        <?php while($c = $categories->fetch_assoc()): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= htmlspecialchars($c['name']) ?></td>
                <td><?= htmlspecialchars($c['description']) ?></td>
                <td>
                    <a href="edit_category.php?id=<?= $c['id'] ?>" class="btn btn-info btn-sm">تعديل</a>
                    <a href="?delete=<?= $c['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('تأكيد الحذف؟')">حذف</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php">رجوع للوحة التحكم</a>
</div>
</body>
</html>
