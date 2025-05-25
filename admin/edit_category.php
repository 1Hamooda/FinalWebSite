<?php
require_once '../includes/auth.php';
require_role('admin');
require_once '../includes/db.php';
$id = intval($_GET['id']);
$cat = $conn->query("SELECT * FROM category WHERE id=$id")->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $stmt = $conn->prepare("UPDATE category SET name=?, description=? WHERE id=?");
    $stmt->bind_param('ssi', $name, $description, $id);
    $stmt->execute();
    header('Location: categories.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل قسم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>تعديل قسم</h2>
    <form method="post">
        <div class="mb-3">
            <label>اسم القسم</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($cat['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>الوصف</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($cat['description']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
    </form>
    <a href="categories.php">رجوع لإدارة الأقسام</a>
</div>
</body>
</html>
