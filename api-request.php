<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'functions.php';
require_once 'dify.php';

// DB接続
$pdo = db_conn();
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');

    $message = $_POST['message'] ?? '';

    try {
        if (empty($message)) {
            throw new Exception('メッセージを入力してください。');
        }

        // ユーザーメッセージをデータベースに保存
        $stmt = $pdo->prepare("INSERT INTO chat_messages (content, user) VALUES (?, 1)");
        $stmt->execute([$message]);

        // Difyにメッセージを送信し、応答を取得
        $response = sendToDify($message);

        // レスポンスをそのまま返す
        echo $response;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'error' => $e->getMessage()
        ]);
    }

        // データベースに挿入
        //$stmt = $pdo->prepare("INSERT INTO chat_messages (content, user) VALUES (?, 0)");
       // $stmt->execute([$response]);

    /*try {
        // JSONデータを受け取る
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
    
        // 入力データのバリデーション
        if (!isset($data['message']) || !isset($data['user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid input data',
            ]);
            exit;
        }
    
        // データベースに挿入
        $stmt = $pdo->prepare("INSERT INTO chat_messages (content, user) VALUES (?, ?)");
        $stmt->execute([$data['message'], $data['user'] === 'bot' ? 0 : 1]);
    
        // 成功レスポンス
        echo json_encode([
            'status' => 'success',
            'message' => 'Message saved successfully',
        ]);
    } catch (Exception $e) {
        // エラーレスポンス
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
        ]);
    }*/
    exit;
}
