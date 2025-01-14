<?php
session_start();

// POSTデータ取得
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// DB接続
include("functions.php");
$pdo = db_conn();

try {
    // ユーザー認証SQL作成
    $stmt = $pdo->prepare("SELECT email, name, password FROM user WHERE email = :email");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // 認証成功
        $_SESSION['email'] = $user['email'];
        //$_SESSION['name'] = $user['name'];
        header('Location: dashboard.php'); // ダッシュボードへリダイレクト
        exit();
    } else {
        // 認証失敗
        $_SESSION['error_message'] = 'メールアドレスまたはパスワードが間違っています。';
        header('Location: index.php'); // ログインページへ戻る
        exit();
    }
} catch (PDOException $e) {
    // エラーハンドリング
    $_SESSION['error_message'] = 'エラーが発生しました: ' . $e->getMessage();
    header('Location: index.php'); // ログインページへ戻る
    exit();
}

// ログイン処理時に初回ログイン日を設定
$stmt = $pdo->prepare("
    UPDATE user
    SET first_login = CASE 
                        WHEN first_login IS NULL THEN NOW() 
                        ELSE first_login 
                      END
");
$stmt->bindValue( $_SESSION['email'], PDO::PARAM_INT);
$stmt->execute();