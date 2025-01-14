<?php
// mental/functions.php

// セッションの開始（セッションがまだ開始されていない場合のみ開始）
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン状態の確認
if (!function_exists('check_login')) {
    function check_login() {
        if (!isset($_SESSION['email'])) {
            header('Location: index.php');
            exit();
        }
    }
}


// DB接続
function db_conn(){
    try {
    $db_name = "megaphone11_gs_db3";    //データベース名
    $db_id   = "megaphone11_gs_db3";      //アカウント名=データベース名
    $db_pw   = "Usjpkyu7053";          //パスワード：XAMPPはパスワード無し or MAMPはパスワード”root”に修正してください。
    $db_host = "mysql80.megaphone11.sakura.ne.jp"; //DBホスト
    return new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw);//関数の中にあるため$pdo=だと実行されないため外に出す
    } catch (PDOException $e) {
        exit('DB Connection Error:'.$e->getMessage());
    }
  }

// XSS対応
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES);
}

// リダイレクト
if (!function_exists('redirect')) {
    function redirect($file_name) {
        header("Location: ".$file_name);
        exit();
    }
}

// ユーザー情報を取得
if (!function_exists('get_user_info')) {
    function get_user_info($pdo, $email) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->bindValue(':email', $email, PDO::PARAM_INT);
            //$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
}

// メッセージ挿入時の重複確認
function insert_chat_messages($pdo, $email, $content, $user) {
    try {
        // 重複チェック
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM chat_messages WHERE email = :email AND content = :content");
        $stmt_check->bindValue(':email', $email, PDO::PARAM_INT);
        $stmt_check->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt_check->execute();
        $count = $stmt_check->fetchColumn();

        if ($count > 0) {
            return "Duplicate entry detected: The same message already exists.";
        }

        // 挿入処理
        $stmt = $pdo->prepare("INSERT INTO chat_messages (content, user) VALUES ( :content, :user)");
        //$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':user', $user, PDO::PARAM_INT);
        $stmt->execute();
        return "Message inserted successfully.";
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return "Error inserting message.";
    }
}

// functions.php に追加
function get_next_actions($pdo, $email) {
    try {
        $stmt = $pdo->prepare(
            "SELECT * FROM next_actions na 
             JOIN user u ON na.user_email = u.email 
             WHERE u.email = ? AND na.status = 'active' 
             ORDER BY na.display_order ASC LIMIT 1"
        );
        $stmt->execute([$email]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log('Next actions error: ' . $e->getMessage());
        return false;
    }
}

?>
