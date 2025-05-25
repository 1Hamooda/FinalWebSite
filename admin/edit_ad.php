<?php
require_once '../includes/auth.php';
require_role('admin');
require_once '../includes/db.php';
$id = intval($_GET['id']);
$ad = $conn->query("SELECT * FROM advertisements WHERE id=$id")->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = $_POST['link'];
    $position = $_POST['position'];
    $active = isset($_POST['active']) ? 1 : 0;
    $image_path = $ad['image_path'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imgName = time() . '_' . basename($_FILES['image']['name']);
        $target = '../News WebPage/Photos/' . $imgName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image_path = $imgName;
        }
    }
    $stmt = $conn->prepare("UPDATE advertisements SET image_path=?, link=?, position=?, active=? WHERE id=?");
    $stmt->bind_param('sssii', $image_path, $link, $position, $active, $id);
    $stmt->execute();
    header('Location: ads.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل إعلان</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>تعديل إعلان</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>رابط الإعلان</label>
            <input type="text" name="link" class="form-control" value="<?= htmlspecialchars($ad['link']) ?>">
        </div>
        <div class="mb-3">
            <label>موضع الإعلان</label>
            <select name="position" class="form-control">
                <option value="header" <?= $ad['position']=='header'?'selected':'' ?>>رأس الصفحة</option>
                <option value="sidebar" <?= $ad['position']=='sidebar'?'selected':'' ?>>الجانب</option>
                <option value="footer" <?= $ad['position']=='footer'?'selected':'' ?>>تذييل</option>
            </select>
        </div>
        <div class="mb-3">
            <label>صورة الإعلان</label>
            <input type="file" name="image" class="form-control">
            <?php if ($ad['image_path']): ?>
                <img src="../News WebPage/Photos/<?= htmlspecialchars($ad['image_path']) ?>" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="active" class="form-check-input" id="activeCheck" <?= $ad['active']?'checked':'' ?>>
            <label class="form-check-label" for="activeCheck">نشط</label>
        </div>
        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
    </form>
    <a href="ads.php">رجوع لإدارة الإعلانات</a>
</div>
</body>
</html>
