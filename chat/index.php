<?php
require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../auth.php";


$username = current_user_name($conn);
$uid = current_user_id();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $searchTerm = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT name FROM users WHERE name != ? AND name LIKE ?");
    $stmt->bind_param("ss", $username, $searchTerm);
} else {
    $stmt = $conn->prepare("SELECT name FROM users WHERE name != ?");
    $stmt->bind_param("s", $username);
}

$stmt->execute();
$result = $stmt->get_result();

$selectedUser = '';

if (isset($_GET['user'])) {
    $selectedUser = $_GET['user'];
    $selectedUser    = mysqli_real_escape_string($conn, $selectedUser);
    $showChatBox = true;
} else {
    $showChatBox = false;
}

layout_header("Клубове – Alumni Club", $uid, $username);
?>

<!DOCTYPE html>
<link href="<?= app_url('assets/chat_style.css') ?>" rel="stylesheet">
<body>
<div class="container-chat">
    <div class="account-info">
        <div class="welcome">
            <h2>Welcome, <?php echo ucfirst($username); ?>!</h2>

            <div class="notification-wrapper">
                <button class="bell-btn" id="notification-btn">
                    🔔
                    <span class="badge" id="notification-badge">0</span>
                </button>

                <div class="notification-dropdown" id="notification-dropdown">
                    <ul id="notification-list"></ul>
                </div>
            </div>
        </div>
    </div>
    <form action="" method="get">
        <input type="text" class="user-search" name="search" placeholder="Seartch user by name">
    </form>
        <div class="user-list">
            <h2>Select a User to Chat With:</h2>
            <ul>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $user = $row['name'];
                        $displayUser = ucfirst($user);
                        echo "<li><a href='" . app_url('chat/index.php?user=' . urlencode($user)) . "'>$displayUser</a></li>";
                }
            } else {
                echo "<li>No users found.</li>";
            }
            ?>
            </ul>
        </div>
    </div>

    <?php if ($showChatBox): ?>
    <div class="chat-box" id="chat-box">
        <div class="chat-box-header">
            <h2><?php echo ucfirst($selectedUser); ?></h2>
            <button class="close-btn" onclick="closeChat()">✖</button>
        </div>
        <div class="chat-box-body" id="chat-box-body">
        </div>
        <form class="chat-form" id="chat-form">
            <input type="hidden" id="sender" value="<?php echo $username; ?>">
            <input type="hidden" id="receiver" value="<?php echo $selectedUser; ?>">
            <input type="text" id="message" placeholder="Type your message..." required>
            <button type="submit">Send</button>
        </form>
    </div>
</div>
<?php endif; ?>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>

    function closeChat() {
        document.getElementById("chat-box").style.display = "none";
        window.location.href = "<?= app_url('chat/index.php') ?>";
    }


    function toggleChatBox() {
    var chatBox = document.getElementById("chat-box");
    if (chatBox.style.display === "none") {
        chatBox.style.display = "block";
    } else {
        chatBox.style.display = "none";
    }
}


function fetchMessages() {
            var sender = $('#sender').val();
            var receiver = $('#receiver').val();

            $.ajax({
                url: 'fetch_messages.php',
                type: 'POST',
                data: {sender: sender, receiver: receiver},
                success: function(data) {
                    $('#chat-box-body').html(data);
                    scrollChatToBottom();
                }
            });
        }

function fetchNotifications() {
            $.ajax({
                url: 'fetch_notifications.php',
                dataType: 'json',
                success: function(data) {
                    let badge = $('#notification-badge');
                    let list  = $('#notification-list');

                    list.empty();
                    if (data.count === 0) {
                        $('#notification-list').html('<li style="padding:10px;">No notifications</li>');
                    }
                    if (data.count > 0) {
                        badge.text(data.count).show();

                        data.notifications.forEach(n => {
                            list.append(`
                                <li>
                                    <a href="?user=${n.sender}">
                                        ${n.sender} (${n.unread_count})
                                    </a>
                                </li>
                            `);
                        });

                    } else {
                        badge.hide();
                    }
                }
            });
        }

        $('#notification-btn').on('click', function (e) {
            e.stopPropagation();
            $('#notification-dropdown').toggle();
        });

        $(document).on('click', function () {
            $('#notification-dropdown').hide();
        });

        function scrollChatToBottom() {
            var chatBox = $('#chat-box-body');
            chatBox.scrollTop(chatBox.prop("scrollHeight"));
        }


        $(document).ready(function() {

            fetchMessages();
            fetchNotifications();
            setInterval(fetchMessages, 3000);
            setInterval(fetchNotifications, 3000);
        });

            $('#chat-form').submit(function(e) {
            e.preventDefault();
            var sender = $('#sender').val();
            var receiver = $('#receiver').val();
            var message = $('#message').val();

            $.ajax({
                url: 'submit_message.php',
                type: 'POST',
                data: {sender: sender, receiver: receiver, message: message},
                success: function() {
                    $('#message').val('');
                    fetchMessages();
                }
            });

        });


</script>

</body>
</html>
<?php layout_footer(); ?>