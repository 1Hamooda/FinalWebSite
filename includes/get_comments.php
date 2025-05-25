<?php
require_once '../includes/db.php';
header('Content-Type: application/json');
$news_id = isset($_GET['news_id']) ? intval($_GET['news_id']) : 0;
$comments = $conn->query("SELECT username, content, created_at FROM comments WHERE news_id=$news_id ORDER BY created_at DESC");
$data = [];
while ($row = $comments->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
