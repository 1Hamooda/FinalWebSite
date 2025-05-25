<?php
require_once '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_id = intval($_POST['news_id']);
    $username = trim($_POST['username']);
    $content = trim($_POST['content']);
    if ($news_id && $username && $content) {
        $stmt = $conn->prepare("INSERT INTO comments (news_id, username, content) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $news_id, $username, $content);
        $stmt->execute();
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
