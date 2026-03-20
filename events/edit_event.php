<?php
require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../auth.php";
require_login();

$uid = current_user_id();
$user_name = current_user_name($conn);
$event_id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($event_id <= 0) die("Невалиден id.");

$st = $conn->prepare("SELECT * FROM events WHERE id = ?");
$st->bind_param("i", $event_id);
$st->execute();
$event = $st->get_result()->fetch_assoc();
if (!$event) die("Събитието не е намерено.");

if ((int)$event["owner_id"] !== $uid) { http_response_code(403); die("Потребители, които не са създали събитието нямат право да го редактират."); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $desc = trim($_POST["description"] ?? "");
    $evdate = trim($_POST['evdate'] ?? "");
	  $place = trim($_POST['place'] ?? "");
	  $time = trim($_POST['appt'] ?? "");
    $st2 = $conn->prepare("UPDATE events SET info = ?, place = ?, date = ?, time = ? WHERE id = ?");
    $st2->bind_param("ssssi", $desc, $place, $evdate, $time, $event_id);
    $st2->execute();
    redirect('events/index.php?');
    exit;
}
layout_header("Създай събитие – Alumni Club", $uid, $user_name);
?>
<h2>Редакция на събитие </h2>
<form method="post" class="card" style="max-width:820px;">

  <a class="btn btn--sm" href="<?= app_url('events/index.php') ?>">← Назад</a>
  <div style="height:10px;"></div>

    <div class="field">
      <label class="form-label">Описание</label>
      <textarea class="form-control" name="description" rows="4"></textarea>
    </div>

  <div class="field">
    <label class="form-label">Място, на което ще се проведе събитието</label>
    <input class="form-control" name="place" maxlength="150" required>
  </div>

  <div class="field">
    <label for="evdate">Day of the event:</label>
	<input type="date" id="evdate" name="evdate" required>
  </div>

   <div class="field">
	<label for="appt">Time of the event:</label>
	<input type="time" id="appt" name="appt">
  </div>


  <button class="btn btn--primary" type="submit">Запази</button>
  <a class="btn btn-link" href="<?= app_url('events/index.php') ?>">Назад</a>
</form>
<?php layout_footer(); ?>