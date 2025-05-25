<?php
require_once '../includes/auth.php';
require_role('admin');
require_once '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $stmt = $conn->prepare("INSERT INTO category (name, description) VALUES (?, ?)");
    $stmt->bind_param('ss', $name, $description);
    $stmt->execute();
    header('Location: categories.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة قسم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>إضافة قسم جديد</h2>
    <form method="post">
        <div class="mb-3">
            <label>اسم القسم</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>الوصف</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">إضافة</button>
    </form>
    <a href="categories.php">رجوع لإدارة الأقسام</a>
</div>
</body>
</html>
