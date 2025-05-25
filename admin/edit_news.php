<?php
require_once '../includes/auth.php';
require_role('admin');
require_once '../includes/db.php';
$id = intval($_GET['id']);
$news = $conn->query("SELECT * FROM news WHERE id=$id")->fetch_assoc();
$cats = $conn->query("SELECT * FROM category");
$authors = $conn->query("SELECT * FROM users WHERE role='author'");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $category_id = intval($_POST['category_id']);
    $author_id = intval($_POST['author_id']);
    $status = $_POST['status'];
    $image = $news['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imgName = time() . '_' . basename($_FILES['image']['name']);
        $target = '../News WebPage/Photos/' . $imgName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $imgName;
        }
    }
    $stmt = $conn->prepare("UPDATE news SET title=?, body=?, image=?, category_id=?, author_id=?, status=? WHERE id=?");
    $stmt->bind_param('sssissi', $title, $body, $image, $category_id, $author_id, $status, $id);
    $stmt->execute();
    header('Location: news.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل خبر</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>تعديل خبر</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>العنوان</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($news['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label>المحتوى</label>
            <textarea name="body" class="form-control" rows="5" required><?= htmlspecialchars($news['body']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>القسم</label>
            <select name="category_id" class="form-control" required>
                <?php while($c = $cats->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id']==$news['category_id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>الكاتب</label>
            <select name="author_id" class="form-control" required>
                <?php while($a = $authors->fetch_assoc()): ?>
                    <option value="<?= $a['id'] ?>" <?= $a['id']==$news['author_id']?'selected':'' ?>><?= htmlspecialchars($a['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>الحالة</label>
            <select name="status" class="form-control" required>
                <option value="pending" <?= $news['status']=='pending'?'selected':'' ?>>قيد المراجعة</option>
                <option value="published" <?= $news['status']=='published'?'selected':'' ?>>منشور</option>
                <option value="rejected" <?= $news['status']=='rejected'?'selected':'' ?>>مرفوض</option>
            </select>
        </div>
        <div class="mb-3">
            <label>صورة الخبر</label>
            <input type="file" name="image" class="form-control">
            <?php if ($news['image']): ?>
                <img src="../News WebPage/Photos/<?= htmlspecialchars($news['image']) ?>" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
    </form>
    <a href="news.php">رجوع لإدارة الأخبار</a>
</div>
</body>
</html>
