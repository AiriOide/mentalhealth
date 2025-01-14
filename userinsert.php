<?php
//1. POSTデータ取得
$name   = $_POST["name"];
$birthday  = $_POST["birthday"];
$gender = $_POST["gender"];
$job    = $_POST["job"];
$email    = $_POST["email"];
$password    = $_POST["password"]; //追加されています

//2. DB接続します
include("functions.php");
$pdo = db_conn();

// 3. データ重複チェック
$stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$email_count = $stmt->fetchColumn();

if ($email_count > 0) {
    // 重複エラー
    echo "このメールアドレスは既に登録されています。";
    exit();
}

//３．データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO user(name,birthday,gender,job,email,password)VALUES(:name,:birthday,:gender,:job,:email,:password)");
$stmt->bindValue(':name', $name, PDO::PARAM_STR);      //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':birthday', $birthday, PDO::PARAM_STR);    //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':gender', $gender, PDO::PARAM_STR);        //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':job', $job, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':email', $email, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':password',  password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行

      // 4. user テーブルにデータを挿入
      /*$stmt_user = $pdo->prepare("INSERT INTO user (name, birthday, gender, job, email) VALUES (:name, :birthday, :gender, :job, :email)");
      $stmt_user->bindValue(':name', $name, PDO::PARAM_STR);
      $stmt_user->bindValue(':birthday', $birthday, PDO::PARAM_STR);
      $stmt_user->bindValue(':gender', $gender, PDO::PARAM_STR);
      $stmt_user->bindValue(':job', $job, PDO::PARAM_STR);
      $stmt_user->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt_user->execute();*/
  
      // 5. login テーブルにデータを挿入
      /*$hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $stmt_login = $pdo->prepare("INSERT INTO login (email, password) VALUES (:email, :password)");
      $stmt_login->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt_login->bindValue(':password', $hashed_password, PDO::PARAM_STR);
      $stmt_login->execute();*/

//４．データ登録処理後
// 5. 実行

if ($status == false) {
    sql_error($stmt);
} else {
    echo "登録が完了しました。";
    redirect("index.php");
}
?>