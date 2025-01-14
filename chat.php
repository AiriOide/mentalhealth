<?php
session_start();
require_once 'functions.php';

// DB接続
$pdo = db_conn();
check_login();

// 現在ログインしているユーザーのemailを取得
$email = $_SESSION['email'] ?? null;
//var_dump($_SESSION);

// emailがセットされていない場合はログインページにリダイレクト
if (!$email) {
    header("Location: index.php");
    exit();
}

// メッセージ送信処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content'])) {
    $content = trim($_POST['content']); // メッセージをトリム

    if (!empty($content)) {
        // メッセージを保存
        $stmt = $pdo->prepare("INSERT INTO chat_messages (email, content, user) VALUES (?, ?, 1)");
        $stmt->execute([$email, $content]);

        // 再読み込みしてフォーム再送信を防ぐ
        header("Location: chat.php");
        exit();
    }
}
?>

<?php include 'header.php'; ?>

<h1>カウンセリングチャット</h1>

<div id="chat-messages">
    <?php
    // 現在のユーザーのemailに基づいてメッセージを取得
    $stmt = $pdo->prepare("SELECT * FROM chat_messages WHERE email = ? ORDER BY created_at ASC");
    $stmt->execute([$email]);
    $messages = $stmt->fetchAll();

    // メッセージを表示
    foreach ($messages as $message): ?>
        <div class="message <?php echo $message['user'] ? 'user-message' : 'bot-message'; ?>">
            <span><?php echo htmlspecialchars($message['content']); ?></span>
            <small><?php echo htmlspecialchars($message['created_at']); ?></small>
        </div>
    <?php endforeach; ?>
</div>

<form id="chat-form" method="post" action="chat.php">
    <input type="text" name="content" id="chat-input" placeholder="メッセージを入力..." required>
    <button type="submit">送信</button>
</form>

<script src="js/main.js"></script>

<?php include 'footer.php'; ?>
