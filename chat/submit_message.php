<?php
require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../auth.php";

$username = current_user_name($conn);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   

    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $message = $_POST['message'];

    $sql = "INSERT INTO chat_messages (sender, receiver, message, is_read) VALUES ('$sender', '$receiver', '$message', 0)";
    $conn->query($sql);
    $conn->close();
}


?>