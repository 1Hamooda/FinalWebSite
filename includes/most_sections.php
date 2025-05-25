<?php
require_once '../includes/db.php';
header('Content-Type: application/json');
// Most read
$most_read = $conn->query("SELECT id, title, image FROM news WHERE status='published' ORDER BY views DESC LIMIT 5");
$most_read_arr = $most_read->fetch_all(MYSQLI_ASSOC);
// Most commented
$most_commented = $conn->query("SELECT n.id, n.title, n.image, COUNT(c.id) as comment_count FROM news n LEFT JOIN comments c ON n.id = c.news_id WHERE n.status='published' GROUP BY n.id ORDER BY comment_count DESC LIMIT 5");
$most_commented_arr = $most_commented->fetch_all(MYSQLI_ASSOC);
echo json_encode([
    'mostRead' => $most_read_arr,
    'mostCommented' => $most_commented_arr
]);
?>
