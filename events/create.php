<?php
require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../auth.php";
require_login();


$uid = current_user_id();
$user_name = current_user_name($conn);
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $desc = trim($_POST["description"] ?? "");
    $evdate = trim($_POST['evdate'] ?? "");
	  $place = trim($_POST['place'] ?? "");
	  $time = trim($_POST['appt'] ?? "");

    if ($name === "") {
        $error = "Името е задължително.";
    } else {
        $st = $conn->prepare("INSERT INTO events (name, info, date, place, time, owner_id) VALUES (?, ?, ?, ?, ?, ?)");
        $st->bind_param("sssssi", $name, $desc, $evdate, $place, $time, $uid);
        $st->execute();

        $event_id = $conn->insert_id;

        $st2 = $conn->prepare("INSERT IGNORE INTO event_members (event_id, user_id) VALUES (?, ?)");
        $st2->bind_param("ii", $event_id, $uid);
        $st2->execute();

        flash_set("success", "Събитието е създаден успешно!");
        redirect('events/index.php');
        exit;
    }
}
layout_header("Създай събитие – Alumni Club", $uid, $user_name);
?>

<a class="btn btn--sm" href="<?= app_url('events/index.php') ?>">← Назад</a>
<div style="height:10px;"></div>

<h1 class="page__title">Създай събитие</h1>

<?php if ($error): ?>
  <div class="toast toast--danger">
    <div class="toast__dot" aria-hidden="true"></div>
    <div class="toast__msg"><?= e($error) ?></div>
  </div>
<?php endif; ?>

<form method="post" class="card" style="max-width:820px;">
  <div class="card__pad">
    <?= csrf_field() ?>

    <div class="field">
       <label class="form-label">Име на събитие</label>
    <input class="form-control" name="name" maxlength="150" required>
    </div>

    <div style="height:12px;"></div>

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


    <div style="height:16px;"></div>

    <button class="btn btn--primary" type="submit">Създай</button>
  </div>
</form>

<?php layout_footer(); ?>
