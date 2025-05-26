<?php
require_once '../includes/auth.php';
require_role('admin');
require_once '../includes/db.php';
$cats = $conn->query("SELECT * FROM category");
$authors = $conn->query("SELECT * FROM users WHERE role='author'");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $category_id = intval($_POST['category_id']);
    $author_id = intval($_POST['author_id']);
    $status = $_POST['status'];
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imgName = time() . '_' . basename($_FILES['image']['name']);
        $target = '../News WebPage/Photos/' . $imgName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $imgName;
        }
    }
    $stmt = $conn->prepare("INSERT INTO news (title, body, image, category_id, author_id, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssiss', $title, $body, $image, $category_id, $author_id, $status);
    $stmt->execute();
    // AUDIT LOG
    $user_id = $_SESSION['user_id'];
    $news_id = $conn->insert_id;
    $action_type = 'add';
    $details = 'Added news: ' . $title;
    $conn->query("INSERT INTO audit_log (user_id, news_id, action_type, details) VALUES ($user_id, $news_id, '$action_type', '" . $conn->real_escape_string($details) . "')");
    header('Location: news.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة خبر</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>إضافة خبر جديد</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>العنوان</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>المحتوى</label>
            <textarea name="body" class="form-control" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label>القسم</label>
            <select name="category_id" class="form-control" required>
                <?php while($c = $cats->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>الكاتب</label>
            <select name="author_id" class="form-control" required>
                <?php while($a = $authors->fetch_assoc()): ?>
                    <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>الحالة</label>
            <select name="status" class="form-control" required>
                <option value="pending">قيد المراجعة</option>
                <option value="published">منشور</option>
                <option value="rejected">مرفوض</option>
            </select>
        </div>
        <div class="mb-3">
            <label>صورة الخبر</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">إضافة</button>
    </form>
    <a href="news.php">رجوع لإدارة الأخبار</a>
</div>
</body>
</html>
