<?php
require_once '../includes/auth.php';
require_role('author');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم الكاتب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>لوحة تحكم الكاتب</h2>
    <ul>
        <li><a href="news.php">أخباري</a></li>
        <li><a href="add_news.php">إضافة خبر جديد</a></li>
    </ul>
    <a href="../logout.php">تسجيل الخروج</a>
</div>
</body>
</html>
