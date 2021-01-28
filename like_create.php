<?php
// var_dump($_GET);
// exit();

// 関数ファイルの読み込み
include("functions.php");

// GETデータ取得
$user_id = $_GET['user_id'];
$todo_id = $_GET['todo_id'];

// DB接続
$pdo = connect_to_db();

$sql = "SELECT COUNT(*) FROM like_table WHERE user_id=:user_id AND todo_id=:todo_id";



// $sql = 'INSERT INTO like_table(id, user_id, todo_id,  created_at) VALUES(NULL, :user_id, :todo_id, sysdate())';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindValue(':todo_id', $todo_id, PDO::PARAM_STR);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
    // header("Location:todo_read.php");
    // exit();
    $like_count = $stmt->fetch();
    // var_dump($like_count[0]);
    // exit();
}
if ($like_count[0] != 0) {
    //いいねされている条件
    $sql = "DELETE FROM like_table WHERE user_id=:user_id AND todo_id=:todo_id";
} else {
    //いいねされていない条件
    $sql = 'INSERT INTO like_table(id, user_id, todo_id,  created_at) VALUES(NULL, :user_id, :todo_id, sysdate())';
}


//SQL文を実行するまでは常にセットなので、都度、準備＆実行が必要
// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$stmt->bindValue(':todo_id', $todo_id, PDO::PARAM_STR);
$status = $stmt->execute();
// データ登録処理後
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
    header("Location:todo_read.php");
    exit();
}
