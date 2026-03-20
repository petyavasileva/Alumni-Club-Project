<?php
require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../auth.php";

$username = current_user_name($conn);

$sql = "SELECT sender, COUNT(*) as unread_count FROM chat_messages WHERE receiver = '$username' AND is_read = 0 GROUP BY sender";
$result = $conn->query($sql);

$notifications = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
    $total += (int)$row['unread_count'];
}

echo json_encode(['count' => $total,'notifications' => $notifications]);
?>