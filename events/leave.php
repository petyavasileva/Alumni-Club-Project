<?php
require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../auth.php";
require_login();


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['event_id'])) {
    $event_id = (int)$_POST['event_id'];
    $user_id = current_user_id();

    $stmt = $conn->prepare("DELETE FROM event_members WHERE event_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $user_id);

    if ($stmt->execute()) {
        flash_set("success", "Успешно отписване от събитието.");
    } else {
        flash_set("danger", "Възникна грешка.");
    }

     redirect('events/index.php');
    exit;
}

