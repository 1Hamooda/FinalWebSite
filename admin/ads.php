<?php
require_once '../includes/auth.php';
require_role('admin');
require_once '../includes/db.php';
// Delete ad
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM advertisements WHERE id=$id");
}
$ads = $conn->query("SELECT * FROM advertisements");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الإعلانات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>إدارة الإعلانات</h2>
    <a href="add_ad.php" class="btn btn-success mb-3">إضافة إعلان</a>
    <table class="table table-bordered">
        <thead><tr><th>الرقم</th><th>الصورة</th><th>الرابط</th><th>الموضع</th><th>نشط</th><th>إجراءات</th></tr></thead>
        <tbody>
        <?php while($ad = $ads->fetch_assoc()): ?>
            <tr>
                <td><?= $ad['id'] ?></td>
                <td><?php if ($ad['image_path']): ?><img src="../News WebPage/Photos/<?= htmlspecialchars($ad['image_path']) ?>" width="80"><?php endif; ?></td>
                <td><?= htmlspecialchars($ad['link']) ?></td>
                <td><?= htmlspecialchars($ad['position']) ?></td>
                <td><?= $ad['active'] ? 'نعم' : 'لا' ?></td>
                <td>
                    <a href="edit_ad.php?id=<?= $ad['id'] ?>" class="btn btn-info btn-sm">تعديل</a>
                    <a href="?delete=<?= $ad['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('تأكيد الحذف؟')">حذف</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="index.php">رجوع للوحة التحكم</a>
</div>
</body>
</html>
