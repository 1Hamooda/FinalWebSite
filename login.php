<?php
require_once 'includes/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Database error: ' . $conn->error);
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            if ($user['role'] === 'admin') {
                header('Location: admin/index.php');
            } elseif ($user['role'] === 'editor') {
                header('Location: editor/index.php');
            } else {
                header('Location: author/index.php');
            }
            exit();
        }
    }
    $error = 'بيانات الدخول غير صحيحة';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>تسجيل الدخول</h2>
    <?php if (!empty($error)) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
    <form method="post">
        <div class="mb-3">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>كلمة المرور</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">دخول</button>
        <a href="front-page.php" class="btn btn-secondary ms-2">العودة للصفحة الرئيسية</a>
    </form>
</div>
</body>
</html>
