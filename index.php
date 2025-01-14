<?php
session_start();

// 既にログインしている場合は dashboard.php へリダイレクト
if (isset($_SESSION['id'])) {
    header('Location: dashboard.php');
    exit();
}

// エラーメッセージの初期化
$error_message = '';
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // エラーメッセージを1回表示後削除
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - メンタルヘルスカウンセリング</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .login-container {
            max-width: 400px;
            width: 90%;
            margin: 2rem auto;
            padding: 2rem;
        }
        .button-container {
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>メンタルヘルスカウンセリング</h1>
        <p>ログインして始めましょう</p>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form method="POST" action="insert.php">
            <label><input type="email" name="email" placeholder="メールアドレス" required></label>
            <label><input type="password" name="password" placeholder="パスワード" required></label>
            <button type="submit">ログイン</button>
        </form>
        <div class="button-container">
            <a href="user.php" class="button">初めての方はこちら</a>
        </div>
    </div>
</body>
</html>