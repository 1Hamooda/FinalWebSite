<?php
require_once '../includes/auth.php';
require_role('admin');
require_once '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = $_POST['link'];
    $position = $_POST['position'];
    $active = isset($_POST['active']) ? 1 : 0;
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imgName = time() . '_' . basename($_FILES['image']['name']);
        $target = '../News WebPage/Photos/' . $imgName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image_path = $imgName;
        }
    }
    $stmt = $conn->prepare("INSERT INTO advertisements (image_path, link, position, active, category_id) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param('sssii', $image_path, $link, $position, $active, $category_id);
        $stmt->execute();
    } else {
        die('Database error: ' . $conn->error);
    }
    header('Location: ads.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة إعلان</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>إضافة إعلان جديد</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>رابط الإعلان</label>
            <input type="text" name="link" class="form-control">
        </div>
        <div class="mb-3">
            <label>صورة الإعلان</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="mb-3">
            <label>الفئة</label>
            <select name="category_id" class="form-control">
                <option value="">اختر فئة</option>
                <?php
                $categories = $conn->query("SELECT id, name FROM category");
                while ($row = $categories->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                }
                ?>
            </select>
        </div>
        <input type="hidden" name="position" value="sidebar">
        <div class="mb-3 form-check">
            <input type="checkbox" name="active" class="form-check-input" id="activeCheck" checked>
            <label class="form-check-label" for="activeCheck">نشط</label>
        </div>
        <button type="submit" class="btn btn-primary">إضافة</button>
    </form>
    <a href="ads.php">رجوع لإدارة الإعلانات</a>
</div>
</body>
</html>
