<?php
// dashboard.php
session_start();
require_once 'functions.php';

check_login();

// DB接続
$pdo = db_conn();

// ユーザー情報取得
$email = $_SESSION['email'];
$user_info = get_user_info($pdo, $email);

if (!$user_info) {
    echo "ユーザー情報が見つかりません。";
    exit();
}

// アクション完了の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_id'])) {
    complete_action($pdo, $_POST['action_id'], $email);
    header('Location: dashboard.php');
    exit();
}

// 初回ログイン日を取得
$stmt = $pdo->prepare("SELECT first_login FROM user WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// 初回ログイン日からチャット日を計算
$firstLogin = new DateTime($user['first_login']);
$today = new DateTime();
$interval = $firstLogin->diff($today);
$weekNumber = floor($interval->days / 7);
$nextChatDay = $firstLogin->modify('+' . $weekNumber . ' week')->format('Y-m-d');

// チャット日が今日かどうかを確認
$isChatDay = ($nextChatDay === $today->format('Y-m-d'));

include 'header.php';
?>

<h1>ダッシュボード</h1>
<p>こんにちは、<?php echo htmlspecialchars($user_info['name']); ?>さん！</p>

<div class="chat-section">
    <?php if ($isChatDay): ?>
        <h2>今日はチャット日です</h2>
        <p>カウンセラーとチャットしましょう</p>
        <a href="chat.php" class="button">チャットを開始</a>
    <?php else: ?>
        <h2>次のチャット日は <?php echo $nextChatDay; ?> です</h2>
        <p>その間、ネクストアクションに取り組みましょう</p>
    <?php endif; ?>
</div>

<div class="next-actions">
    <h2>ネクストアクション</h2>
    <?php
    // ユーザーの次のアクションを取得
    $action = get_next_actions($pdo, $email);

    if ($action): ?>
        <p><?php echo htmlspecialchars($action['action']); ?></p>
        <form method="post">
            <input type="hidden" name="action_id" value="<?php echo $action['id']; ?>">
            <button type="submit">完了</button>
        </form>
    <?php else: ?>
        <p>現在のネクストアクションはありません。</p>
    <?php endif; ?>
    <a href="tasks.php" class="button">すべてのタスクを見る</a>
</div>

<?php include 'footer.php'; ?>