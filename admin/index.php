<?php
require_once '../includes/auth.php';
require_role('admin');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المدير</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>لوحة تحكم المدير</h2>
    <ul>
        <li><a href="users.php">إدارة المستخدمين</a></li>
        <li><a href="news.php">إدارة الأخبار</a></li>
        <li><a href="categories.php">إدارة الأقسام</a></li>
        <li><a href="ads.php">إدارة الإعلانات</a></li>
        <li><a href="audit.php">سجل التعديلات</a></li>
    </ul>
    <a href="../logout.php">تسجيل الخروج</a>
</div>
</body>
</html>
