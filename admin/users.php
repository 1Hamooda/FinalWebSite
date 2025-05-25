<?php
require_once '../includes/auth.php';
require_role('admin');
require_once '../includes/db.php';
// List users
$users = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة المستخدمين</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>إدارة المستخدمين</h2>
    <a href="add_user.php" class="btn btn-success mb-3">إضافة مستخدم</a>
    <table class="table table-bordered">
        <thead><tr><th>الرقم</th><th>الاسم</th><th>البريد</th><th>الدور</th><th>إجراءات</th></tr></thead>
        <tbody>
        <?php while($u = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $u['id'] ?>" class="btn btn-info btn-sm">تعديل</a>
                    <a href="delete_user.php?id=<?= $u['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('تأكيد الحذف؟')">حذف</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php">رجوع للوحة التحكم</a>
</div>
</body>
</html>
