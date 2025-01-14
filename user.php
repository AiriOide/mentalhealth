<?php
session_start();

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
    <title>ユーザー登録 - メンタルヘルスカウンセリング</title>
    <link href="css/styles.css" rel="stylesheet">
    <style>
        .registration-container {
            max-width: 600px;
            width: 90%;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button-container {
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h1>メンタルヘルスカウンセリング</h1>
        <p>登録して始めましょう</p>
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form method="POST" action="userinsert.php">
            <div class="form-group">
                <label for="name">名前：</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="birthday">誕生日：</label>
                <input type="date" id="birthday" name="birthday" required>
            </div>
            <div class="form-group">
                <label for="gender">性別：</label>
                <select id="gender" name="gender" required>
                    <option value="">選択してください</option>
                    <option value="male">男性</option>
                    <option value="female">女性</option>
                    <option value="other">その他</option>
                </select>
            </div>
            <div class="form-group">
                <label for="job">職業：</label>
                <input type="text" id="job" name="job" required>
            </div>
            <div class="form-group">
                <label for="email">メールアドレス：</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード：</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="button-container">
                <input type="submit" value="登録" class="button">
            </div>
        </form>
        <div class="button-container">
            <a href="index.php" class="button">ログインページに戻る</a>
        </div>
    </div>
</body>
</html>