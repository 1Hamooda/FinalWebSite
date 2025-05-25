<?php
require_once '../includes/auth.php';
require_role('author');
require_once '../includes/db.php';
$cats = $conn->query("SELECT * FROM category");
$author_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $category_id = intval($_POST['category_id']);
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imgName = time() . '_' . basename($_FILES['image']['name']);
        $target = '../News WebPage/Photos/' . $imgName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $imgName;
        }
    }
    $stmt = $conn->prepare("INSERT INTO news (title, body, image, category_id, author_id, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param('sssii', $title, $body, $image, $category_id, $author_id);
    $stmt->execute();
    header('Location: news.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <title>إضافة خبر</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
<div class='container mt-4'>
    <h2>إضافة خبر جديد</h2>
    <form method='post' enctype='multipart/form-data'>
        <div class='mb-3'>
            <label>العنوان</label>
            <input type='text' name='title' class='form-control' required>
        </div>
        <div class='mb-3'>
            <label>المحتوى</label>
            <textarea name='body' class='form-control' rows='5' required></textarea>
        </div>
        <div class='mb-3'>
            <label>القسم</label>
            <select name='category_id' class='form-control' required>
                <?php while($c = $cats->fetch_assoc()): ?>
                    <option value='<?= $c['id'] ?>'><?= htmlspecialchars($c['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class='mb-3'>
            <label>صورة الخبر</label>
            <input type='file' name='image' class='form-control'>
        </div>
        <button type='submit' class='btn btn-primary'>إضافة</button>
    </form>
    <a href='news.php'>رجوع لأخباري</a>
</div>
</body>
</html>
