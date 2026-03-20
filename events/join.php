<?php
require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../auth.php";
require_login();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['event_id'])) {
    $event_id = (int)$_POST['event_id'];
    $user_id = current_user_id();

    $stmt = $conn->prepare("INSERT IGNORE INTO event_members (event_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $event_id, $user_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            flash_set("success", "Успешно се присъединихте към събитието!");
        } else {
            flash_set("warning", "Вече сте член на това събитие.");
        }
    } else {
        flash_set("danger", "Грешка при записването.");
    }

    redirect('events/index.php');
    exit;
}