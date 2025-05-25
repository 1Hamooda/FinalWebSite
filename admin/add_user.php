<?php
require_once '../includes/auth.php';
require_role('admin');
require_once '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $name, $email, $password, $role);
    $stmt->execute();
    header('Location: users.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة مستخدم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>إضافة مستخدم جديد</h2>
    <form method="post">
        <div class="mb-3">
            <label>الاسم</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>كلمة المرور</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>الدور</label>
            <select name="role" class="form-control" required>
                <option value="author">كاتب</option>
                <option value="editor">محرر</option>
                <option value="admin">مدير</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">إضافة</button>
    </form>
    <a href="users.php">رجوع لإدارة المستخدمين</a>
</div>
</body>
</html>
