<?php
require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../auth.php";


$uid = current_user_id();
$username = current_user_name($conn);
$q = trim((string)($_GET["q"] ?? ""));
$only_mine = isset($_GET["mine"]) && $_GET["mine"] === "1";

if ($only_mine && $uid !== null) {
    $like = "%" . $q . "%";
    $st = $conn->prepare("
        SELECT c.*,
        (SELECT COUNT(*) FROM event_members em WHERE em.event_id = c.id) AS members_count
        FROM events c
        JOIN event_members em2 ON em2.event_id = c.id AND em2.user_id = ?
        WHERE c.name LIKE ?
        ORDER BY c.created_at DESC
    ");
    $st->bind_param("is", $uid, $like);
    $st->execute();
    $res = $st->get_result();
} else {
    if ($q !== "") {
        $like = "%" . $q . "%";
        $st = $conn->prepare("
            SELECT c.*,
            (SELECT COUNT(*) FROM event_members em WHERE em.event_id = c.id) AS members_count
            FROM events c
            WHERE c.name LIKE ?
            ORDER BY c.created_at DESC
        ");
        $st->bind_param("s", $like);
        $st->execute();
        $res = $st->get_result();
    } else {
        $res = $conn->query("
            SELECT c.*,
            (SELECT COUNT(*) FROM event_members em WHERE em.event_id = c.id) AS members_count
            FROM events c
            ORDER BY c.created_at DESC
        ");
    }
}

layout_header("Събития – Alumni Club", $uid, $username);
?>
<link href="<?= app_url('assets/events.css') ?>" rel="stylesheet">

<div style="display:flex; justify-content:space-between; align-items:flex-end; gap:12px; flex-wrap:wrap;">
  <div>
    <h1 class="page__title" style="margin-bottom:6px;">Събития</h1>
    <div class="muted">Откривай общности и се включвай с 1 клик.</div>
  </div>

  <?php if ($uid): ?>
    <a class="btn btn--primary" href="<?= app_url('events/create.php') ?>">+ Създай събитие </a>
  <?php endif; ?>
</div>

<div style="height:14px;"></div>

<form method="get" class="card" style="margin-bottom:14px;">
  <div class="card__pad" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
    <input class="input" name="q" placeholder="Търси по име или описание…" value="<?= e($q) ?>" style="flex:1 1 260px;">
    <?php if ($uid): ?>
      <label class="badge" style="cursor:pointer; user-select:none;">
        <input type="checkbox" name="mine" value="1" <?= $only_mine ? "checked" : "" ?> style="margin:0 8px 0 0;">
        Само моите
      </label>
    <?php endif; ?>
    <button class="btn btn--soft" type="submit">Търси</button>
    <a class="btn" href="<?= app_url('events/index.php') ?>">Изчисти</a>
  </div>
</form>

<div class="row g-3">
  <div class="events-grid">
    <?php 
    
    while  ($c = $res->fetch_assoc()):
       $current_event_id = $c['id'];
      $is_member = false;
      $is_owner = ($uid !== null && (int)$c["owner_id"] === $uid);

    if ($uid) {
        $check_stmt = $conn->prepare("SELECT 1 FROM event_members WHERE event_id = ? AND user_id = ? LIMIT 1");
        $check_stmt->bind_param("ii", $current_event_id, $uid);
        $check_stmt->execute();
        $is_member = (bool)$check_stmt->get_result()->fetch_assoc();
    }
    ?>
    <div class="event-card">
        <div class="event-card-header">
            <h3><?= htmlspecialchars($c["name"]) ?></h3>
        </div>

        <div class="event-card-body">
            <div class="members-badge">
                 <?= (int)$c["members_count"] ?> Members
            </div>

            <div class="event-meta">
                <div class="meta-item">
                    <span><strong>Място:</strong> <?= htmlspecialchars($c["place"]) ?></span>
                </div>
                <div class="meta-item">
                    <span><strong>Дата:</strong>  <?= htmlspecialchars($c["date"]) ?></span>
                </div>
                <div class="meta-item">
                    <span><strong>Час:</strong>  <?= htmlspecialchars($c["time"]) ?></span>
                </div>
            </div>

            <hr>

            <div class="event-info">
                <p><?= htmlspecialchars($c["info"]) ?></p>
            </div>
        </div>

        <div class="event-card-footer">
             <?php if ($uid && ($uid != $is_owner)): ?>
        <?php if ($is_member): ?>
          <form action="leave.php" method="POST">
            <input type="hidden" name="event_id" value="<?= $current_event_id ?>">
            <button type="submit" class="btn-leave">Напусни</button>
          </form>
        <?php else: ?>
          <form action="join.php" method="POST">
            <input type="hidden" name="event_id" value="<?= $current_event_id ?>">
            <button type="submit" class="btn-join">Присъедини се</button>
          </form>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($uid && ($uid == $is_owner)): ?>
      <a class="btn-redact" href="<?= app_url('events/edit_event.php?id=' . (int)$current_event_id) ?>">
            <i class="btn-redact"></i> Редактирай евент
      </a>
    <?php endif; ?>
        </div>
    </div>
<?php endwhile; ?>
</div>
<?php layout_footer(); ?>