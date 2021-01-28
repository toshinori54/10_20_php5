<?php

// 送信確認
// var_dump($_POST);
// exit();

// session_start();
include("functions.php");
// check_session_id();

// 項目入力のチェック
// 値が存在しないor空で送信されてきた場合はNGにする
if (
    !isset($_POST['username']) || $_POST['username'] == '' ||
    !isset($_POST['telphone']) || $_POST['telphone'] == ''
) {
    // 項目が入力されていない場合はここでエラーを出力し，以降の処理を中止する
    echo json_encode(["error_msg" => "no input"]);
    exit();
}

// 受け取ったデータを変数に入れる
$username = $_POST['username'];
$telphone = $_POST['telphone'];
$reservation = $_POST['reservation'];
$request = $_POST['request'];

// DB接続
$pdo = connect_to_db();

// データ登録SQL作成
// `created_at`と`updated_at`には実行時の`sysdate()`関数を用いて実行時の日時を入力する
$sql = 'INSERT INTO todo_table(id, username, telphone, reservation, request, created_at, updated_at) 
VALUES(NULL, :username, :telphone, :reservation,:request, sysdate(), sysdate())';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':telphone', $telphone, PDO::PARAM_STR);
$stmt->bindValue(':reservation', $reservation, PDO::PARAM_STR);
$stmt->bindValue(':request', $request, PDO::PARAM_STR);
$status = $stmt->execute();

// var_dump($_POST);
// exit();

// データ登録処理後
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
    header("Location:seitai_input.php");
    exit();
}
